<?php

namespace Tests\Feature;

use App\Models\Finance\CashRegister;
use App\Models\Finance\CashTransfer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CashTransferCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected CashRegister $senderBox;
    protected CashRegister $receiverBox;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->senderBox = CashRegister::create([
            'name' => 'Caja Remitente',
            'user_id' => $this->user->id,
            'initial_balance' => 500000,
            'current_balance' => 500000,
            'status' => 'open',
        ]);

        $this->receiverBox = CashRegister::create([
            'name' => 'Caja Admin Receptora',
            'user_id' => $this->user->id,
            'initial_balance' => 1000000,
            'current_balance' => 1000000,
            'status' => 'open',
        ]);

        Passport::actingAs($this->user);
    }

    public function test_create_cash_transfer_decrements_sender_balance()
    {
        $mutation = '
            mutation CreateTransfer($sender: ID!, $receiver: ID!, $amount: Float!) {
                createCashTransfer(sender_cash_register_id: $sender, receiver_cash_register_id: $receiver, amount: $amount) {
                    success
                    message
                    cashTransfer {
                        id
                        amount
                        status
                    }
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'sender' => $this->senderBox->id,
                'receiver' => $this->receiverBox->id,
                'amount' => 100000,
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.createCashTransfer.success', true);

        // Remitente debe haber bajado de 500.000 a 400.000
        $this->assertEquals(400000, (float) $this->senderBox->fresh()->current_balance);
        // Receptor sigue en 1.000.000 porque está pending
        $this->assertEquals(1000000, (float) $this->receiverBox->fresh()->current_balance);
    }

    public function test_accept_cash_transfer_increments_receiver_balance()
    {
        $transfer = CashTransfer::create([
            'sender_cash_register_id' => $this->senderBox->id,
            'receiver_cash_register_id' => $this->receiverBox->id,
            'amount' => 150000,
            'status' => 'pending',
        ]);

        // Remitente bajó a 350.000 por la creación
        $this->assertEquals(350000, (float) $this->senderBox->fresh()->current_balance);

        $mutation = '
            mutation AcceptTransfer($id: ID!) {
                acceptCashTransfer(id: $id) {
                    success
                    message
                    cashTransfer {
                        id
                        status
                    }
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => ['id' => $transfer->id],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.acceptCashTransfer.success', true);

        // Receptor subió de 1.000.000 a 1.150.000
        $this->assertEquals(1150000, (float) $this->receiverBox->fresh()->current_balance);
    }

    public function test_delete_accepted_cash_transfer_reverts_both_balances()
    {
        $transfer = CashTransfer::create([
            'sender_cash_register_id' => $this->senderBox->id,
            'receiver_cash_register_id' => $this->receiverBox->id,
            'amount' => 200000,
            'status' => 'pending',
        ]);

        // Aceptar la transferencia
        $transfer->update(['status' => 'accepted']);

        // Verificar saldos antes de eliminar:
        // Sender: 500k - 200k = 300k
        // Receiver: 1.000k + 200k = 1.200k
        $this->assertEquals(300000, (float) $this->senderBox->fresh()->current_balance);
        $this->assertEquals(1200000, (float) $this->receiverBox->fresh()->current_balance);

        $mutation = '
            mutation DeleteTransfer($id: ID!) {
                deleteCashTransfer(id: $id) {
                    success
                    message
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => ['id' => $transfer->id],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.deleteCashTransfer.success', true);

        // REVERSA:
        // Sender recupera los 200k -> 500k
        // Receiver descuenta los 200k -> 1.000k
        $this->assertEquals(500000, (float) $this->senderBox->fresh()->current_balance);
        $this->assertEquals(1000000, (float) $this->receiverBox->fresh()->current_balance);
        $this->assertDatabaseMissing('cash_transfers', ['id' => $transfer->id]);
    }

    public function test_delete_pending_cash_transfer_returns_money_to_sender()
    {
        $transfer = CashTransfer::create([
            'sender_cash_register_id' => $this->senderBox->id,
            'receiver_cash_register_id' => $this->receiverBox->id,
            'amount' => 80000,
            'status' => 'pending',
        ]);

        // Sender: 500k - 80k = 420k
        $this->assertEquals(420000, (float) $this->senderBox->fresh()->current_balance);
        $this->assertEquals(1000000, (float) $this->receiverBox->fresh()->current_balance);

        $mutation = '
            mutation DeleteTransfer($id: ID!) {
                deleteCashTransfer(id: $id) {
                    success
                    message
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => ['id' => $transfer->id],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.deleteCashTransfer.success', true);

        // Sender recupera los 80k -> 500k
        // Receiver sin cambios -> 1.000k
        $this->assertEquals(500000, (float) $this->senderBox->fresh()->current_balance);
        $this->assertEquals(1000000, (float) $this->receiverBox->fresh()->current_balance);
        $this->assertDatabaseMissing('cash_transfers', ['id' => $transfer->id]);
    }

    public function test_update_cash_transfer_amount_adjusts_balances()
    {
        $transfer = CashTransfer::create([
            'sender_cash_register_id' => $this->senderBox->id,
            'receiver_cash_register_id' => $this->receiverBox->id,
            'amount' => 100000,
            'status' => 'pending',
        ]);

        $transfer->update(['status' => 'accepted']);

        // Sender: 400k, Receiver: 1.100k
        $this->assertEquals(400000, (float) $this->senderBox->fresh()->current_balance);
        $this->assertEquals(1100000, (float) $this->receiverBox->fresh()->current_balance);

        // Actualizar monto de 100k a 150k
        $mutation = '
            mutation UpdateTransfer($id: ID!, $amount: Float) {
                updateCashTransfer(id: $id, amount: $amount) {
                    success
                    message
                    cashTransfer {
                        id
                        amount
                    }
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'id' => $transfer->id,
                'amount' => 150000,
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.updateCashTransfer.success', true);

        // Diff es 50k adicional:
        // Sender: 400k - 50k = 350k
        // Receiver: 1.100k + 50k = 1.150k
        $this->assertEquals(350000, (float) $this->senderBox->fresh()->current_balance);
        $this->assertEquals(1150000, (float) $this->receiverBox->fresh()->current_balance);
    }

    public function test_query_cash_transfers_paginated_with_order_by()
    {
        CashTransfer::create([
            'sender_cash_register_id' => $this->senderBox->id,
            'receiver_cash_register_id' => $this->receiverBox->id,
            'amount' => 50000,
            'status' => 'pending',
        ]);

        $query = '
            query CashTransfersPaginated($first: Int!, $page: Int) {
                cashTransfers(first: $first, page: $page, orderBy: [{ column: "created_at", order: DESC }]) {
                    data {
                        id
                        amount
                        status
                        senderCashRegister {
                            id
                            name
                        }
                    }
                    paginatorInfo {
                        total
                    }
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $query,
            'variables' => [
                'first' => 10,
                'page' => 1,
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.cashTransfers.paginatorInfo.total', 1);
    }

    public function test_cancel_cash_transfer_reverts_balances_and_records_reason()
    {
        $transfer = CashTransfer::create([
            'sender_cash_register_id' => $this->senderBox->id,
            'receiver_cash_register_id' => $this->receiverBox->id,
            'amount' => 200000,
            'status' => 'pending',
        ]);
        $transfer->update(['status' => 'accepted']);

        // Sender: 300k, Receiver: 1.200k
        $this->assertEquals(300000, (float) $this->senderBox->fresh()->current_balance);
        $this->assertEquals(1200000, (float) $this->receiverBox->fresh()->current_balance);

        $mutation = '
            mutation CancelTransfer($id: ID!, $reason: String!) {
                cancelCashTransfer(id: $id, reason: $reason) {
                    success
                    message
                    cashTransfer {
                        id
                        status
                        notes
                    }
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'id' => $transfer->id,
                'reason' => 'Error en monto enviado',
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.cancelCashTransfer.success', true)
            ->assertJsonPath('data.cancelCashTransfer.cashTransfer.status', 'cancelled');

        // Saldos devueltos a estado original (Sender: 500k, Receiver: 1.000k)
        $this->assertEquals(500000, (float) $this->senderBox->fresh()->current_balance);
        $this->assertEquals(1000000, (float) $this->receiverBox->fresh()->current_balance);

        $freshTransfer = $transfer->fresh();
        $this->assertEquals('cancelled', $freshTransfer->status);
        $this->assertStringContainsString('[MOTIVO ANULACIÓN]: Error en monto enviado', $freshTransfer->notes);
    }
}
