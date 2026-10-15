<?php

namespace Tests\Feature;

use App\Models\Finance\CashTransfer;
use App\Models\Finance\Vault;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class VaultBankDepositTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Vault $vault;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->vault = Vault::create([
            'name' => 'Bóveda Principal',
            'initial_balance' => 1000000,
            'current_balance' => 1000000,
            'notes' => 'Bóveda central de tesorería',
        ]);

        // Asignar al usuario como administrador de la bóveda
        $this->vault->admins()->attach($this->user->id);

        Passport::actingAs($this->user);
    }

    public function test_deposit_vault_to_bank_decrements_vault_balance_and_records_deposit()
    {
        $mutation = '
            mutation DepositToBank($input: DepositVaultToBankInput!) {
                depositVaultToBank(input: $input) {
                    success
                    message
                    cashTransfer {
                        id
                        amount
                        status
                        bank_name
                        account_number
                        reference_number
                        deposit_date
                        receiver_name
                        receiver_kind
                    }
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'input' => [
                    'vault_id' => (string) $this->vault->id,
                    'amount' => 400000,
                    'bank_name' => 'Bancolombia',
                    'account_number' => '987-654321-01',
                    'reference_number' => 'COMP-883921',
                    'deposit_date' => '2026-10-14',
                    'notes' => 'Consignación por cierre semanal a Bancolombia',
                ],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.depositVaultToBank.success', true)
            ->assertJsonPath('data.depositVaultToBank.cashTransfer.amount', 400000)
            ->assertJsonPath('data.depositVaultToBank.cashTransfer.status', 'accepted')
            ->assertJsonPath('data.depositVaultToBank.cashTransfer.bank_name', 'Bancolombia')
            ->assertJsonPath('data.depositVaultToBank.cashTransfer.account_number', '987-654321-01')
            ->assertJsonPath('data.depositVaultToBank.cashTransfer.reference_number', 'COMP-883921')
            ->assertJsonPath('data.depositVaultToBank.cashTransfer.receiver_kind', 'Bank');

        // El saldo de la bóveda debe haber disminuido de 1.000.000 a 600.000
        $this->assertEquals(600000, (float) $this->vault->fresh()->current_balance);

        // Verificar persistencia en base de datos
        $this->assertDatabaseHas('cash_transfers', [
            'sender_type' => Vault::class,
            'sender_id' => $this->vault->id,
            'amount' => 400000,
            'status' => 'accepted',
            'bank_name' => 'Bancolombia',
            'account_number' => '987-654321-01',
            'reference_number' => 'COMP-883921',
        ]);
    }

    public function test_deposit_vault_to_bank_fails_when_insufficient_balance()
    {
        $mutation = '
            mutation DepositToBank($input: DepositVaultToBankInput!) {
                depositVaultToBank(input: $input) {
                    success
                    message
                    cashTransfer {
                        id
                    }
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'input' => [
                    'vault_id' => (string) $this->vault->id,
                    'amount' => 1500000, // Mayor que 1.000.000
                    'bank_name' => 'Davivienda',
                ],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.depositVaultToBank.success', false);

        // El saldo no debe haber cambiado
        $this->assertEquals(1000000, (float) $this->vault->fresh()->current_balance);
    }

    public function test_deposit_vault_to_bank_fails_when_user_not_authorized()
    {
        // Usuario sin asignar a la bóveda y con router asignado (canSeeAllData = false)
        $otherUser = User::factory()->create();
        $router = \App\Models\Router::create([
            'code' => 'R-TEST-' . uniqid(),
            'name' => 'Router Test',
        ]);
        $otherUser->routers()->attach($router->id);
        Passport::actingAs($otherUser);

        $mutation = '
            mutation DepositToBank($input: DepositVaultToBankInput!) {
                depositVaultToBank(input: $input) {
                    success
                    message
                    cashTransfer {
                        id
                    }
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'input' => [
                    'vault_id' => (string) $this->vault->id,
                    'amount' => 100000,
                    'bank_name' => 'Bancolombia',
                ],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.depositVaultToBank.success', false);

        // El saldo permanece intacto
        $this->assertEquals(1000000, (float) $this->vault->fresh()->current_balance);
    }

    public function test_cancel_bank_deposit_reverts_vault_balance()
    {
        // 1. Crear el depósito inicial (el saldo baja de 1.000.000 a 700.000)
        $depositMutation = '
            mutation DepositToBank($input: DepositVaultToBankInput!) {
                depositVaultToBank(input: $input) {
                    success
                    cashTransfer {
                        id
                    }
                }
            }
        ';

        $depositResponse = $this->postJson('/graphql', [
            'query' => $depositMutation,
            'variables' => [
                'input' => [
                    'vault_id' => (string) $this->vault->id,
                    'amount' => 300000,
                    'bank_name' => 'Banco de Bogotá',
                    'reference_number' => 'REF-12345',
                ],
            ],
        ]);

        $depositResponse->assertStatus(200);
        $transferId = $depositResponse->json('data.depositVaultToBank.cashTransfer.id');

        $this->assertEquals(700000, (float) $this->vault->fresh()->current_balance);

        // 2. Anular la consignación
        $cancelMutation = '
            mutation CancelTransfer($id: ID!, $reason: String!) {
                cancelCashTransfer(id: $id, reason: $reason) {
                    success
                    message
                    cashTransfer {
                        id
                        status
                    }
                }
            }
        ';

        $cancelResponse = $this->postJson('/graphql', [
            'query' => $cancelMutation,
            'variables' => [
                'id' => $transferId,
                'reason' => 'Comprobante bancario duplicado por error',
            ],
        ]);

        $cancelResponse->assertStatus(200)
            ->assertJsonPath('data.cancelCashTransfer.success', true)
            ->assertJsonPath('data.cancelCashTransfer.cashTransfer.status', 'cancelled');

        // 3. El saldo de la bóveda debe haberse restaurado a 1.000.000
        $this->assertEquals(1000000, (float) $this->vault->fresh()->current_balance);

        $this->assertDatabaseHas('cash_transfers', [
            'id' => $transferId,
            'status' => 'cancelled',
        ]);
    }
}
