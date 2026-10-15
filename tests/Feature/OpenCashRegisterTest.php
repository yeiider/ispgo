<?php

namespace Tests\Feature;

use App\Models\Finance\CashRegister;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class OpenCashRegisterTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected CashRegister $cashRegister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->cashRegister = CashRegister::create([
            'name' => 'Caja Zona Test',
            'user_id' => $this->user->id,
            'initial_balance' => 0,
            'current_balance' => 0,
            'status' => CashRegister::STATUS_CLOSED,
        ]);

        Passport::actingAs($this->user);
    }

    public function test_open_cash_register_with_initial_balance_and_optional_notes()
    {
        $mutation = '
            mutation OpenRegister($id: ID!, $initialBalance: Float, $notes: String) {
                openCashRegister(id: $id, initial_balance: $initialBalance, notes: $notes) {
                    id
                    name
                    status
                    initialBalance
                    currentBalance
                    notes
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'id' => $this->cashRegister->id,
                'initialBalance' => 150000.0,
                'notes' => 'Base entregada por administración en billetes pequeños',
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.openCashRegister.status', 'OPEN')
            ->assertJsonPath('data.openCashRegister.initialBalance', 150000)
            ->assertJsonPath('data.openCashRegister.currentBalance', 150000)
            ->assertJsonPath('data.openCashRegister.notes', 'Base entregada por administración en billetes pequeños');

        $fresh = $this->cashRegister->fresh();
        $this->assertEquals('open', $fresh->status);
        $this->assertEquals(150000, (float) $fresh->initial_balance);
        $this->assertEquals(150000, (float) $fresh->current_balance);
        $this->assertEquals('Base entregada por administración en billetes pequeños', $fresh->notes);
    }

    public function test_open_cash_register_without_notes_is_optional()
    {
        $mutation = '
            mutation OpenRegister($id: ID!, $initialBalance: Float) {
                openCashRegister(id: $id, initial_balance: $initialBalance) {
                    id
                    status
                    initialBalance
                    notes
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $mutation,
            'variables' => [
                'id' => $this->cashRegister->id,
                'initialBalance' => 80000.0,
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.openCashRegister.status', 'OPEN')
            ->assertJsonPath('data.openCashRegister.initialBalance', 80000)
            ->assertJsonPath('data.openCashRegister.notes', null);

        $fresh = $this->cashRegister->fresh();
        $this->assertEquals(80000, (float) $fresh->initial_balance);
        $this->assertNull($fresh->notes);
    }

    public function test_daily_report_exposes_initial_balance_notes()
    {
        $this->cashRegister->update([
            'status' => CashRegister::STATUS_OPEN,
            'initial_balance' => 200000,
            'current_balance' => 200000,
            'notes' => 'Observación de apertura matutina',
        ]);

        $query = '
            query DailyReport($cashRegisterId: ID) {
                myDailyCollectionReport(cash_register_id: $cashRegisterId) {
                    initial_balance
                    initial_balance_notes
                    total_neto
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $query,
            'variables' => [
                'cashRegisterId' => $this->cashRegister->id,
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.myDailyCollectionReport.initial_balance', 200000)
            ->assertJsonPath('data.myDailyCollectionReport.initial_balance_notes', 'Observación de apertura matutina');
    }
}
