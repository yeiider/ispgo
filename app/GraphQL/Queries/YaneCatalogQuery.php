<?php

namespace App\GraphQL\Queries;

use App\Services\Config\ConfigService;

class YaneCatalogQuery
{
    protected ConfigService $service;

    public function __construct()
    {
        $this->service = new ConfigService();
    }

    /**
     * Return the full assistant catalog (contacts, coverage, offices, costs,
     * FAQs, TV channels) read from the editable config (core_config_data).
     */
    public function resolve($_, array $args)
    {
        $paths = [
            'contactos.web_url' => 'asistente_yane/contactos/web_url',
            'contactos.email' => 'asistente_yane/contactos/email',
            'contactos.payment_url' => 'asistente_yane/contactos/payment_url',
            'cobertura.ciudades' => 'asistente_yane/cobertura/ciudades',
            'cobertura.sinonimos' => 'asistente_yane/cobertura/sinonimos',
            'oficinas.listado' => 'asistente_yane/oficinas/listado',
            'costos.listado' => 'asistente_yane/costos_instalacion/listado',
            'faqs.listado' => 'asistente_yane/faqs/listado',
            'canales.listado' => 'asistente_yane/canales_tv/listado',
        ];

        $values = $this->service->getValues(array_values($paths));

        $val = function (string $key) use ($values, $paths) {
            return $values[$paths[$key]] ?? null;
        };

        return [
            'contacts' => [
                'web_url' => $val('contactos.web_url'),
                'email' => $val('contactos.email'),
                'payment_url' => $val('contactos.payment_url'),
            ],
            'coverage' => $this->parseZones($val('cobertura.ciudades')),
            'synonyms' => $this->parseZones($val('cobertura.sinonimos')),
            'offices' => $this->parsePairs($val('oficinas.listado'), ':', 'city', 'address'),
            'installation_costs' => $this->parsePairs($val('costos.listado'), ':', 'zone', 'cost'),
            'faqs' => $this->parsePairs($val('faqs.listado'), '=>', 'question', 'answer'),
            'tv_channels' => $this->parseChannels($val('canales.listado')),
        ];
    }

    /**
     * Parse "Ciudad: zona1, zona2" lines into [{city, zones[]}].
     */
    protected function parseZones(?string $raw): array
    {
        $result = [];
        foreach ($this->lines($raw) as $line) {
            [$city, $rest] = $this->split($line, ':');
            $zones = $rest === null
                ? []
                : array_values(array_filter(array_map('trim', explode(',', $rest)), 'strlen'));
            $result[] = ['city' => trim($city), 'zones' => $zones];
        }
        return $result;
    }

    /**
     * Parse "left delim right" lines into [{leftKey, rightKey}].
     */
    protected function parsePairs(?string $raw, string $delim, string $leftKey, string $rightKey): array
    {
        $result = [];
        foreach ($this->lines($raw) as $line) {
            [$left, $right] = $this->split($line, $delim);
            if ($right === null) {
                continue;
            }
            $result[] = [$leftKey => trim($left), $rightKey => trim($right)];
        }
        return $result;
    }

    /**
     * Parse a comma-separated channel list into [String!].
     */
    protected function parseChannels(?string $raw): array
    {
        if (!$raw) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $raw)), 'strlen'));
    }

    protected function lines(?string $raw): array
    {
        if (!$raw) {
            return [];
        }
        return array_values(array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', $raw)),
            'strlen'
        ));
    }

    /**
     * Split a line on the first occurrence of a delimiter.
     * Returns [left, right|null] (right is null when the delimiter is absent).
     */
    protected function split(string $line, string $delim): array
    {
        $pos = mb_strpos($line, $delim);
        if ($pos === false) {
            return [trim($line), null];
        }
        return [
            mb_substr($line, 0, $pos),
            mb_substr($line, $pos + mb_strlen($delim)),
        ];
    }
}
