<?php

namespace App\GraphQL\Mutations;

use App\Models\ServiceRule;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ServiceRuleMutation
{
    /**
     * Crear una nueva regla de servicio.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return ServiceRule
     */
    public function create($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ServiceRule
    {
        $input = $args['input'];

        $rule = new ServiceRule();
        $rule->service_id = $input['service_id'];
        $rule->type = $input['type'];
        $rule->cycles = $input['cycles'];
        $rule->cycles_used = 0;
        
        if (isset($input['value'])) {
            $rule->value = $input['value'];
        }
        
        if (isset($input['starts_at'])) {
            $rule->starts_at = $input['starts_at'];
        }

        $rule->save();

        return $rule->fresh(['service']);
    }

    /**
     * Actualizar una regla de servicio existente.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return ServiceRule
     */
    public function update($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ServiceRule
    {
        $rule = ServiceRule::findOrFail($args['id']);

        $input = $args['input'];
        
        $fillable = ['type', 'value', 'cycles', 'cycles_used', 'starts_at'];
        
        foreach ($fillable as $field) {
            if (array_key_exists($field, $input)) {
                $rule->{$field} = $input[$field];
            }
        }

        $rule->save();

        return $rule->fresh(['service']);
    }

    /**
     * Eliminar una regla de servicio.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return array
     */
    public function delete($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): array
    {
        $rule = ServiceRule::findOrFail($args['id']);

        $rule->delete();

        return [
            'success' => true,
            'message' => 'Regla de servicio eliminada correctamente.',
        ];
    }

    /**
     * Reiniciar los ciclos usados de una regla a cero.
     * Útil para reactivar una regla que ya alcanzó su límite.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return ServiceRule
     */
    public function resetCycles($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ServiceRule
    {
        $rule = ServiceRule::findOrFail($args['id']);

        $rule->cycles_used = 0;
        $rule->save();

        return $rule->fresh(['service']);
    }
}
