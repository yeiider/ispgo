<?php

namespace App\GraphQL\Queries;

use App\Models\BillingNovedad;
use App\Models\ServiceRule;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class BillingQuery
{
    // ==================== SERVICE RULES ====================

    /**
     * Lista paginada de reglas de servicio.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return LengthAwarePaginator
     */
    public function serviceRules($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): LengthAwarePaginator
    {
        $query = ServiceRule::query();

        if (!empty($args['service_id'])) {
            $query->where('service_id', $args['service_id']);
        }

        if (!empty($args['type'])) {
            $query->where('type', $args['type']);
        }

        if (!empty($args['active_only']) && $args['active_only']) {
            $query->active();
        }

        $first = $args['first'] ?? 15;
        $page = $args['page'] ?? 1;

        return $query->orderBy('created_at', 'desc')->paginate($first, ['*'], 'page', $page);
    }

    /**
     * Obtener una regla de servicio por ID.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return ServiceRule|null
     */
    public function serviceRule($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?ServiceRule
    {
        return ServiceRule::with(['service'])->find($args['id']);
    }

    /**
     * Obtener todas las reglas activas de un servicio.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return Collection
     */
    public function activeServiceRules($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection
    {
        return ServiceRule::where('service_id', $args['service_id'])
            ->active()
            ->with(['service'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // ==================== BILLING NOVEDADES ====================

    /**
     * Lista paginada de novedades de facturación.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return LengthAwarePaginator
     */
    public function billingNovedades($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): LengthAwarePaginator
    {
        $query = BillingNovedad::query();

        if (!empty($args['service_id'])) {
            $query->where('service_id', $args['service_id']);
        }

        if (!empty($args['customer_id'])) {
            $query->where('customer_id', $args['customer_id']);
        }

        if (!empty($args['type'])) {
            $query->where('type', $args['type']);
        }

        if (isset($args['applied'])) {
            $query->where('applied', $args['applied']);
        }

        if (!empty($args['effective_period'])) {
            $query->whereDate('effective_period', $args['effective_period']);
        }

        $first = $args['first'] ?? 15;
        $page = $args['page'] ?? 1;

        return $query
            ->with(['service', 'customer', 'invoice', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate($first, ['*'], 'page', $page);
    }

    /**
     * Obtener una novedad de facturación por ID.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return BillingNovedad|null
     */
    public function billingNovedad($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?BillingNovedad
    {
        return BillingNovedad::with(['service', 'customer', 'invoice', 'creator'])->find($args['id']);
    }

    /**
     * Obtener novedades pendientes (no aplicadas) de un servicio.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return Collection
     */
    public function pendingNovedades($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection
    {
        return BillingNovedad::where('service_id', $args['service_id'])
            ->pending()
            ->with(['service', 'customer', 'invoice', 'creator'])
            ->orderBy('effective_period', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtener novedades de un servicio para un periodo específico.
     *
     * @param mixed $root
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return Collection
     */
    public function novedadesByPeriod($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection
    {
        $period = Carbon::parse($args['effective_period']);

        return BillingNovedad::where('service_id', $args['service_id'])
            ->forPeriod($period)
            ->with(['service', 'customer', 'invoice', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
