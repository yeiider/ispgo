<?php

namespace App\GraphQL\Mutations;

use App\Models\Finance\Vault;
use Exception;
use Illuminate\Support\Facades\Log;

class VaultMutations
{
    public function create($_, array $args)
    {
        try {
            $vault = Vault::create([
                'name' => $args['name'],
                'initial_balance' => $args['initial_balance'],
                'current_balance' => $args['initial_balance'],
                'notes' => $args['notes'] ?? null,
            ]);

            if (isset($args['admin_ids'])) {
                $vault->admins()->sync($args['admin_ids']);
            }

            return [
                'success' => true,
                'message' => 'Bóveda creada exitosamente.',
                'vault' => $vault->fresh(['admins'])
            ];
        } catch (Exception $e) {
            Log::error('Error creando vault: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al crear la bóveda: ' . $e->getMessage(),
                'vault' => null
            ];
        }
    }

    public function update($_, array $args)
    {
        try {
            $vault = Vault::findOrFail($args['id']);

            $updateData = [];
            if (isset($args['name'])) {
                $updateData['name'] = $args['name'];
            }
            if (isset($args['notes'])) {
                $updateData['notes'] = $args['notes'];
            }
            if (isset($args['current_balance'])) {
                $updateData['current_balance'] = $args['current_balance'];
            }

            $vault->update($updateData);

            if (isset($args['admin_ids'])) {
                $vault->admins()->sync($args['admin_ids']);
            }

            return [
                'success' => true,
                'message' => 'Bóveda actualizada exitosamente.',
                'vault' => $vault->fresh(['admins'])
            ];
        } catch (Exception $e) {
            Log::error('Error actualizando vault: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al actualizar la bóveda: ' . $e->getMessage(),
                'vault' => null
            ];
        }
    }

    public function delete($_, array $args)
    {
        try {
            $vault = Vault::findOrFail($args['id']);
            $vault->delete();

            return [
                'success' => true,
                'message' => 'Bóveda eliminada exitosamente.'
            ];
        } catch (Exception $e) {
            Log::error('Error eliminando vault: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al eliminar la bóveda: ' . $e->getMessage()
            ];
        }
    }
}
