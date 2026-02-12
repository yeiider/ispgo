<?php

namespace App\GraphQL\Mutations;

use App\Jobs\ProcessCashRegisterClosure;
use App\Models\Finance\CashRegister;
use App\Models\Finance\CashRegisterClosure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GraphQL\Error\Error;

class CashRegisterMutations
{
    /**
     * Crear una nueva caja registradora
     */
    public function create($root, array $args)
    {
        DB::beginTransaction();

        try {
            // Verificar que el usuario tenga acceso al router
            $user = Auth::user();
            $routerIds = $user->getRouterIds();

            // El @spread directive convierte camelCase a snake_case
            $routerId = $args['router_id'] ?? $args['routerId'] ?? null;
            $userId = $args['user_id'] ?? $args['userId'] ?? null;
            $initialBalance = $args['initial_balance'] ?? $args['initialBalance'] ?? 0;

            if (!empty($routerIds) && !in_array($routerId, $routerIds)) {
                throw new Error('No tienes permisos para crear una caja en este router.');
            }

            $cashRegister = CashRegister::create([
                'name' => $args['name'],
                'router_id' => $routerId,
                'user_id' => $userId,
                'initial_balance' => $initialBalance,
                'current_balance' => $initialBalance,
                'status' => CashRegister::STATUS_OPEN,
                'notes' => $args['notes'] ?? null,
            ]);

            DB::commit();

            return $cashRegister->load(['router', 'user', 'creator', 'updater']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Error('Error al crear la caja: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar una caja registradora
     */
    public function update($root, array $args)
    {
        DB::beginTransaction();

        try {
            $cashRegister = CashRegister::findOrFail($args['id']);

            // Verificar permisos
            $user = Auth::user();
            $routerIds = $user->getRouterIds();

            if (!empty($routerIds) && !in_array($cashRegister->router_id, $routerIds)) {
                throw new Error('No tienes permisos para modificar esta caja.');
            }

            $updateData = [];

            if (isset($args['name'])) {
                $updateData['name'] = $args['name'];
            }

            // Soportar ambos formatos
            if (isset($args['user_id']) || isset($args['userId'])) {
                $updateData['user_id'] = $args['user_id'] ?? $args['userId'];
            }

            if (isset($args['current_balance']) || isset($args['currentBalance'])) {
                $updateData['current_balance'] = $args['current_balance'] ?? $args['currentBalance'];
            }

            if (isset($args['notes'])) {
                $updateData['notes'] = $args['notes'];
            }

            $cashRegister->update($updateData);

            DB::commit();

            return $cashRegister->fresh()->load(['router', 'user', 'creator', 'updater']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Error('Error al actualizar la caja: ' . $e->getMessage());
        }
    }

    /**
     * Cerrar una caja y procesar el cierre
     */
    public function close($root, array $args)
    {
        try {
            // Soportar ambos formatos
            $cashRegisterId = $args['cash_register_id'] ?? $args['cashRegisterId'] ?? null;
            $closureDate = $args['closure_date'] ?? $args['closureDate'] ?? null;
            $closingBalance = $args['closing_balance'] ?? $args['closingBalance'] ?? null;

            $cashRegister = CashRegister::findOrFail($cashRegisterId);

            // Verificar permisos
            $user = Auth::user();
            $routerIds = $user->getRouterIds();

            if (!empty($routerIds) && !in_array($cashRegister->router_id, $routerIds)) {
                throw new Error('No tienes permisos para cerrar esta caja.');
            }

            // Verificar que la caja esté abierta
            if ($cashRegister->isClosed()) {
                throw new Error('La caja ya está cerrada.');
            }

            $closureDate = Carbon::parse($closureDate);
            $notes = $args['notes'] ?? null;

            // Verificar si ya existe un cierre para esta fecha
            $existingClosure = CashRegisterClosure::where('cash_register_id', $cashRegister->id)
                ->whereDate('closure_date', $closureDate)
                ->first();

            if ($existingClosure && $existingClosure->status === CashRegisterClosure::STATUS_COMPLETED) {
                throw new Error('Ya existe un cierre completado para esta fecha.');
            }

            // Despachar el job para procesar el cierre de forma asíncrona
            ProcessCashRegisterClosure::dispatch(
                $cashRegister->id,
                $user->id,
                $closureDate,
                $closingBalance,
                $notes
            )->onQueue('redis');

            return [
                'success' => true,
                'message' => 'El cierre de caja se está procesando. Recibirás una notificación cuando esté completo.',
                'closure' => $existingClosure,
            ];
        } catch (\Exception $e) {
            throw new Error('Error al cerrar la caja: ' . $e->getMessage());
        }
    }

    /**
     * Abrir una caja cerrada
     */
    public function open($root, array $args)
    {
        DB::beginTransaction();

        try {
            $cashRegister = CashRegister::findOrFail($args['id']);

            // Verificar permisos
            $user = Auth::user();
            $routerIds = $user->getRouterIds();

            if (!empty($routerIds) && !in_array($cashRegister->router_id, $routerIds)) {
                throw new Error('No tienes permisos para abrir esta caja.');
            }

            // Verificar que la caja esté cerrada
            if ($cashRegister->isOpen()) {
                throw new Error('La caja ya está abierta.');
            }

            // Abrir la caja
            $cashRegister->open();

            // Actualizar el balance inicial con el balance actual
            $cashRegister->initial_balance = $cashRegister->current_balance;
            $cashRegister->save();

            DB::commit();

            return $cashRegister->fresh()->load(['router', 'user', 'creator', 'updater']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Error('Error al abrir la caja: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una caja registradora
     */
    public function delete($root, array $args)
    {
        DB::beginTransaction();

        try {
            $cashRegister = CashRegister::findOrFail($args['id']);

            // Verificar permisos
            $user = Auth::user();
            $routerIds = $user->getRouterIds();

            if (!empty($routerIds) && !in_array($cashRegister->router_id, $routerIds)) {
                throw new Error('No tienes permisos para eliminar esta caja.');
            }

            // Verificar que no tenga cierres asociados
            if ($cashRegister->closures()->count() > 0) {
                throw new Error('No se puede eliminar una caja que tiene cierres de caja asociados.');
            }

            $cashRegister->delete();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Caja eliminada exitosamente.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Error('Error al eliminar la caja: ' . $e->getMessage());
        }
    }
}
