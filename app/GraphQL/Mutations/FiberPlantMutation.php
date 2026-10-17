<?php

namespace App\GraphQL\Mutations;

use App\Models\FiberCable;
use App\Models\FiberNode;
use App\Models\FiberSplice;
use App\Models\FiberStrand;
use App\Models\FiberThirdParty;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Alta y edición de la planta externa: nodos (central, mufla...) y cables troncales.
 */
class FiberPlantMutation
{
    public function createNode($root, array $args): FiberNode
    {
        return FiberNode::create($this->validNode($args['input']))->refresh();
    }

    public function updateNode($root, array $args): FiberNode
    {
        $node = FiberNode::findOrFail($args['id']);
        $node->update($this->validNode($args['input'], $node));

        return $node->refresh();
    }

    public function deleteNode($root, array $args): array
    {
        $node = FiberNode::withCount(['cablesOut', 'cablesIn', 'napBoxes'])->findOrFail($args['id']);
        if ($node->cables_out_count + $node->cables_in_count > 0) {
            return ['success' => false, 'message' => 'El nodo tiene cables conectados: elimínalos o muévelos primero.'];
        }
        if ($node->nap_boxes_count > 0) {
            return ['success' => false, 'message' => "Hay {$node->nap_boxes_count} caja(s) empalmadas en este nodo."];
        }
        $node->delete();

        return ['success' => true, 'message' => 'Nodo eliminado'];
    }

    public function createCable($root, array $args): FiberCable
    {
        $data = $this->validCable($args['input']);

        return FiberCable::create($data)->refresh();
    }

    public function updateCable($root, array $args): FiberCable
    {
        $cable = FiberCable::findOrFail($args['id']);
        $cable->update($this->validCable($args['input'], $cable));

        return $cable->refresh();
    }

    public function deleteCable($root, array $args): array
    {
        $cable = FiberCable::withCount('napBoxes')->findOrFail($args['id']);
        if ($cable->nap_boxes_count > 0) {
            return ['success' => false, 'message' => "Hay {$cable->nap_boxes_count} caja(s) alimentadas por hilos de este cable."];
        }
        $cable->delete();

        return ['success' => true, 'message' => 'Cable eliminado'];
    }

    public function saveStrand($root, array $args): array
    {
        $cable = FiberCable::findOrFail($args['fiber_cable_id']);
        $number = (int) $args['number'];
        $input = array_map(fn ($v) => $v === '' ? null : $v, $args['input']);
        if ($number < 1 || $number > $cable->fiber_count) {
            throw ValidationException::withMessages(['number' => ["El cable tiene {$cable->fiber_count} hilos."]]);
        }
        if ($input['status'] === 'free') {
            FiberStrand::where('fiber_cable_id', $cable->id)->where('number', $number)->delete();

            return ['success' => true, 'message' => "Hilo {$number} libre"];
        }
        Validator::make($input, [
            'status' => ['required', Rule::in(FiberStrand::STATUSES)],
            'usage' => ['nullable', Rule::in(FiberStrand::USAGES)],
            'olt_id' => ['nullable', 'exists:olts,id'],
            'third_party_id' => ['nullable', 'exists:fiber_third_parties,id', Rule::requiredIf($input['status'] === 'leased')],
        ], ['third_party_id.required' => 'Elige a qué tercero se vendió el hilo.'])->validate();

        FiberStrand::updateOrCreate(
            ['fiber_cable_id' => $cable->id, 'number' => $number],
            array_intersect_key($input, array_flip(['status', 'usage', 'olt_id', 'pon_port', 'third_party_id', 'label', 'notes'])),
        );

        return ['success' => true, 'message' => "Hilo {$number} guardado"];
    }

    public function createSplice($root, array $args): array
    {
        $input = $args['input'];
        $node = FiberNode::findOrFail($input['fiber_node_id']);
        $in = FiberCable::findOrFail($input['in_cable_id']);
        $out = FiberCable::findOrFail($input['out_cable_id']);
        // Los dos cables deben pasar por la mufla
        foreach ([$in, $out] as $c) {
            if (!in_array($node->id, [$c->from_node_id, $c->to_node_id])) {
                throw ValidationException::withMessages(['fiber_node_id' => ["El cable {$c->name} no pasa por {$node->name}."]]);
            }
        }
        if ($input['in_strand'] < 1 || $input['in_strand'] > $in->fiber_count || $input['out_strand'] < 1 || $input['out_strand'] > $out->fiber_count) {
            throw ValidationException::withMessages(['in_strand' => ['Número de hilo fuera del cable.']]);
        }
        if (FiberSplice::where('out_cable_id', $out->id)->where('out_strand', $input['out_strand'])->exists()) {
            throw ValidationException::withMessages(['out_strand' => ["El hilo {$input['out_strand']} de {$out->name} ya está empalmado."]]);
        }
        FiberSplice::create(array_intersect_key($input, array_flip((new FiberSplice())->getFillable())));

        return ['success' => true, 'message' => 'Empalme guardado'];
    }

    public function deleteSplice($root, array $args): array
    {
        FiberSplice::findOrFail($args['id'])->delete();

        return ['success' => true, 'message' => 'Empalme eliminado'];
    }

    public function createThirdParty($root, array $args): array
    {
        $input = $this->validThirdParty($args['input'], true);
        $t = FiberThirdParty::create($input);

        return ['success' => true, 'message' => (string) $t->id];
    }

    public function updateThirdParty($root, array $args): array
    {
        FiberThirdParty::findOrFail($args['id'])->update($this->validThirdParty($args['input'], false));

        return ['success' => true, 'message' => 'Tercero actualizado'];
    }

    public function deleteThirdParty($root, array $args): array
    {
        $t = FiberThirdParty::withCount('strands')->findOrFail($args['id']);
        if ($t->strands_count > 0) {
            return ['success' => false, 'message' => "{$t->name} tiene {$t->strands_count} hilo(s) asignados: libéralos primero."];
        }
        $t->delete();

        return ['success' => true, 'message' => 'Tercero eliminado'];
    }

    private function validThirdParty(array $input, bool $creating): array
    {
        $input = array_map(fn ($v) => $v === '' ? null : $v, $input);
        Validator::make($input, [
            'name' => [$creating ? 'required' : 'sometimes', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
        ])->validate();

        return array_intersect_key($input, array_flip((new FiberThirdParty())->getFillable()));
    }

    private function validNode(array $input, ?FiberNode $node = null): array
    {
        $input = array_map(fn ($v) => $v === '' ? null : $v, $input);
        Validator::make($input, [
            'name' => [$node ? 'sometimes' : 'required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('fiber_nodes', 'code')->ignore($node?->id)],
            'type' => ['sometimes', Rule::in(FiberNode::TYPES)],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'olt_id' => ['nullable', 'exists:olts,id'],
            'router_id' => ['nullable', 'exists:routers,id'],
            'capacity_mbps' => ['nullable', 'integer', 'min:0'],
        ])->validate();

        return array_intersect_key($input, array_flip((new FiberNode())->getFillable()));
    }

    private function validCable(array $input, ?FiberCable $cable = null): array
    {
        $input = array_map(fn ($v) => $v === '' ? null : $v, $input);
        Validator::make($input, [
            'name' => [$cable ? 'sometimes' : 'required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('fiber_cables', 'code')->ignore($cable?->id)],
            'kind' => ['sometimes', Rule::in(FiberCable::KINDS)],
            'fiber_count' => ['sometimes', 'integer', 'min:1', 'max:864'],
            'from_node_id' => [$cable ? 'sometimes' : 'required', 'exists:fiber_nodes,id'],
            'to_node_id' => [$cable ? 'sometimes' : 'required', 'exists:fiber_nodes,id', 'different:from_node_id'],
        ])->validate();

        $from = (int) ($input['from_node_id'] ?? $cable?->from_node_id);
        $to = (int) ($input['to_node_id'] ?? $cable?->to_node_id);
        if ($from === $to) {
            throw ValidationException::withMessages(['to_node_id' => ['El cable debe unir dos nodos distintos.']]);
        }
        // La red es un árbol: cada nodo recibe la fibra por un solo cable y sin ciclos
        $otherIn = FiberCable::where('to_node_id', $to)->when($cable, fn ($q) => $q->whereKeyNot($cable->id))->exists();
        if ($otherIn) {
            throw ValidationException::withMessages(['to_node_id' => ['Ese nodo ya recibe la fibra por otro cable.']]);
        }
        if ($this->isUpstream($to, $from)) {
            throw ValidationException::withMessages(['to_node_id' => ['El cable cerraría un ciclo en la red.']]);
        }

        if (array_key_exists('route', $input) && $input['route'] !== null) {
            $route = is_string($input['route']) ? json_decode($input['route'], true) : $input['route'];
            if (!is_array($route)) {
                throw ValidationException::withMessages(['route' => ['El trazado debe ser una lista de puntos.']]);
            }
            $input['route'] = array_values(array_map(function ($p) {
                $lat = $p[0] ?? $p['lat'] ?? null;
                $lng = $p[1] ?? $p['lng'] ?? null;
                if (!is_numeric($lat) || !is_numeric($lng)) {
                    throw ValidationException::withMessages(['route' => ['Punto inválido en el trazado.']]);
                }

                return [round((float) $lat, 7), round((float) $lng, 7)];
            }, $route)) ?: null;
        }

        return array_intersect_key($input, array_flip((new FiberCable())->getFillable()));
    }

    /** true si $candidate está aguas arriba de $node (o es el mismo). */
    private function isUpstream(int $candidate, int $node): bool
    {
        $seen = [];
        $current = $node;
        while ($current && !in_array($current, $seen)) {
            if ($current === $candidate) {
                return true;
            }
            $seen[] = $current;
            $current = (int) FiberCable::where('to_node_id', $current)->value('from_node_id');
        }

        return false;
    }
}
