<?php

namespace App\GraphQL\Queries;

use App\Models\Finance\CashRegister;
use App\Models\Finance\CashTransfer;
use App\Models\Finance\Vault;
use GraphQL\Error\Error;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Histórico de movimientos (entradas y salidas) de una Caja General (Vault).
 */
class VaultTransactionsQuery
{
    public function builder($root, array $args): Builder
    {
        $vault = Vault::findOrFail($args['vault_id']);
        $this->authorize($vault);

        $query = CashTransfer::query()
            ->with(['sender', 'receiver', 'senderCashRegister.user', 'receiverCashRegister.user', 'creator'])
            ->involvingVault($vault->id);

        if (!empty($args['status'])) {
            $query->where('status', $args['status']);
        }

        if (!empty($args['search'])) {
            $this->applySearch($query, trim($args['search']));
        }

        return $query->orderByDesc('updated_at')->orderByDesc('id');
    }

    private function authorize(Vault $vault): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $isVaultAdmin = $vault->admins()->where('users.id', $user->id)->exists();

        if (!$isVaultAdmin && !$user->canSeeAllData()) {
            throw new Error('No tienes permiso para ver el histórico de esta Caja General.');
        }
    }

    /**
     * Búsqueda en servidor (abarca todas las páginas): id, notas, nota de
     * discrepancia, estado, y nombre de la caja / cajero de la contraparte.
     */
    private function applySearch(Builder $query, string $term): void
    {
        $like = '%' . $term . '%';

        $query->where(function (Builder $q) use ($term, $like) {
            if (ctype_digit(ltrim($term, '#'))) {
                $q->orWhere('id', (int) ltrim($term, '#'));
            }

            $q->orWhere('notes', 'like', $like)
              ->orWhere('discrepancy_note', 'like', $like)
              ->orWhere('status', 'like', $like);

            foreach (['sender', 'receiver'] as $side) {
                $q->orWhereHasMorph($side, [CashRegister::class], function (Builder $cr) use ($like) {
                    $cr->where('name', 'like', $like)
                       ->orWhereHas('user', fn (Builder $u) => $u->where('name', 'like', $like));
                });
                $q->orWhereHasMorph($side, [Vault::class], function (Builder $v) use ($like) {
                    $v->where('name', 'like', $like);
                });
            }
        });
    }
}
