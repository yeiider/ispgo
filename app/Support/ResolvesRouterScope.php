<?php

namespace App\Support;

/**
 * Para resolvers de GraphQL: deduce la zona (router) desde los argumentos
 * (router_id, service_id, sn o external_id). Sin ninguno, usa la configuración por defecto.
 */
trait ResolvesRouterScope
{
    protected function routerFromArgs(array $args): ?int
    {
        if (!empty($args['router_id'])) {
            return (int) $args['router_id'];
        }
        if (!empty($args['service_id'])) {
            return RouterScope::fromServiceId($args['service_id']);
        }
        foreach (['sn', 'external_id'] as $key) {
            if (!empty($args[$key])) {
                return RouterScope::fromSn($args[$key]);
            }
        }

        return null;
    }
}
