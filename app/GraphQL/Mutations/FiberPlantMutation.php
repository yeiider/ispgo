<?php

namespace App\GraphQL\Mutations;

use App\Models\FiberCable;
use App\Models\FiberNode;
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
