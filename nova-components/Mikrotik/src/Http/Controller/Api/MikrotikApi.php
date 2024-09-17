<?php

namespace Ispgo\Mikrotik\Http\Controller\Api;

use App\Http\Controllers\Controller;
use App\Models\Services\Plan;
use Illuminate\Http\Request;
use Ispgo\Mikrotik\Services\PlanFormatter;
use Ispgo\Mikrotik\Services\PPPoEProfileManager;
use Exception;

class MikrotikApi extends Controller
{
    protected PPPoEProfileManager $pppoeProfileManager;
    private PlanFormatter $planFormatter;

    public function __construct(PPPoEProfileManager $pppoeProfileManager, PlanFormatter $planFormatter)
    {
        $this->pppoeProfileManager = $pppoeProfileManager;
        $this->planFormatter = $planFormatter;

    }

    /**
     * Obtener todos los planes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlans(): \Illuminate\Http\JsonResponse
    {
        try {
            // Obtener todos los planes
            $plans = Plan::all();
            return response()->json(["data" => $plans], 200);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Sincronizar perfiles PPPoE solo para los planes seleccionados.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncSelectedPPPProfiles(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Obtener los IDs de los planes seleccionados para sincronizar
            $selectedPlanIds = $request->input('plan_ids');
            $selectedPlans = Plan::whereIn('id', $selectedPlanIds)->get();

            // Obtener los perfiles PPP existentes desde MikroTik
            $pppProfiles = $this->pppoeProfileManager->listPPPProfiles();

            // Convertir los nombres de los perfiles PPP a minúsculas con guiones bajos
            $pppProfileNames = array_map(function ($profile) {
                return strtolower(str_replace(' ', '_', $profile['name']));
            }, $pppProfiles);

            // Sincronizar los planes seleccionados que no están en MikroTik
            foreach ($selectedPlans as $plan) {
                $formattedPlanName = strtolower(str_replace(' ', '_', $plan->name));

                if (!in_array($formattedPlanName, $pppProfileNames)) {
                    // Crear el perfil en MikroTik
                    $this->createPPPProfile($plan, $request->input('pool'));
                }
            }

            return response()->json(["message" => "Selected profiles synchronized"], 200);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Crear un perfil PPPoE para un plan que no está sincronizado.
     *
     * @param Plan $plan
     * @param Service $service
     * @return void
     */
    private function createPPPProfile(Plan $plan, string $pool = null, string $typePool = "default"): void
    {
        try {
            // Formatear el plan y el servicio usando PlanFormatter
            $formattedPlan = $this->planFormatter->formatPlan($plan);

            // Preparar los parámetros a enviar a MikroTik
            $params = [
                'name' => strtolower(str_replace(' ', '_', $formattedPlan['plan_name'])),
                'rate-limit' => $formattedPlan['download_speed'] . 'M/' . $formattedPlan['upload_speed'] . 'M', // Ejemplo de formato de velocidad
                'remote-address-pool' => $pool, // Pool de direcciones IP (valor predeterminado)
                'dns-server' => '8.8.8.8,8.8.4.4' // DNS predeterminados
            ];

            // Crear el perfil PPPoE en MikroTik
            $this->pppoeProfileManager->createPPPProfile($params['name'], $params['rate-limit'], $params['local-address'], $params['remote-address-pool'], $params['dns-server']);

            // Marcar el plan como sincronizado en la base de datos
            $plan->is_synchronized = true;
            $plan->save();
        } catch (Exception $e) {
            throw new Exception('Error creating PPP profile: ' . $e->getMessage());
        }
    }
}
