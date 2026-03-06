<?php

namespace App\GraphQL\Mutations;

use App\Models\BillingNovedad;
use App\Models\Invoice\Invoice;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class BillingNovedadMutation
{
    /**
     * Crear una nueva novedad de facturación.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return BillingNovedad
     */
    public function create($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): BillingNovedad
    {
        $input = $args['input'];

        $novedad = new BillingNovedad();
        $novedad->service_id = $input['service_id'];
        $novedad->type = $input['type'];
        
        // El monto puede ser calculado automáticamente por el modelo
        // según el tipo de novedad
        if (isset($input['amount'])) {
            $novedad->amount = $input['amount'];
        }
        
        if (isset($input['description'])) {
            $novedad->description = $input['description'];
        }
        
        if (isset($input['effective_period'])) {
            $novedad->effective_period = $input['effective_period'];
        }
        
        if (isset($input['rule'])) {
            $novedad->rule = $input['rule'];
        }
        
        if (isset($input['product_lines'])) {
            $novedad->product_lines = $input['product_lines'];
        }

        $novedad->save();

        return $novedad->fresh(['service', 'customer', 'invoice', 'creator']);
    }

    /**
     * Actualizar una novedad de facturación existente.
     * Solo novedades no aplicadas pueden ser modificadas.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return BillingNovedad
     * @throws ValidationException
     */
    public function update($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): BillingNovedad
    {
        $novedad = BillingNovedad::findOrFail($args['id']);

        if ($novedad->applied) {
            throw ValidationException::withMessages([
                'id' => ['No se puede modificar una novedad que ya ha sido aplicada a una factura.'],
            ]);
        }

        $input = $args['input'];
        
        $fillable = ['amount', 'description', 'effective_period', 'rule', 'product_lines'];
        
        foreach ($fillable as $field) {
            if (array_key_exists($field, $input)) {
                $novedad->{$field} = $input[$field];
            }
        }

        $novedad->save();

        return $novedad->fresh(['service', 'customer', 'invoice', 'creator']);
    }

    /**
     * Eliminar una novedad de facturación.
     * Solo novedades no aplicadas pueden ser eliminadas.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return array
     * @throws ValidationException
     */
    public function delete($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): array
    {
        $novedad = BillingNovedad::findOrFail($args['id']);

        if ($novedad->applied) {
            throw ValidationException::withMessages([
                'id' => ['No se puede eliminar una novedad que ya ha sido aplicada a una factura.'],
            ]);
        }

        $novedad->delete();

        return [
            'success' => true,
            'message' => 'Novedad de facturación eliminada correctamente.',
        ];
    }

    /**
     * Marcar una novedad como aplicada y asociarla a una factura.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return BillingNovedad
     * @throws ValidationException
     */
    public function markAsApplied($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): BillingNovedad
    {
        $novedad = BillingNovedad::findOrFail($args['id']);

        if ($novedad->applied) {
            throw ValidationException::withMessages([
                'id' => ['Esta novedad ya ha sido aplicada.'],
            ]);
        }

        $invoice = Invoice::findOrFail($args['invoice_id']);

        $novedad->markAsApplied($invoice);

        return $novedad->fresh(['service', 'customer', 'invoice', 'creator']);
    }
}
