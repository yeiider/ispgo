<?php

namespace Ispgo\Mikrotik\Nova\Actions;

use App\Models\Services\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Ispgo\Mikrotik\Settings\MikrotikConfigProvider;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Ispgo\Mikrotik\Services\PlanFormatter;
use Ispgo\Mikrotik\Services\SimpleQueueManager;
use Ispgo\Mikrotik\Services\PPPoEManager;
use Laravel\Nova\Http\Requests\NovaRequest;

class MikrotikAction extends Action
{


    protected SimpleQueueManager $simpleQueueManager;
    protected PPPoEManager $pppoeManager;
    protected PlanFormatter $planFormatter;

    public function __construct()
    {
        $this->simpleQueueManager = new SimpleQueueManager();
        $this->pppoeManager = new PPPoEManager();
        $this->planFormatter = new PlanFormatter();
    }

    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection $models
     * @return mixed
     * @throws \Exception
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            /**
             * @var Service $service
             **/
            $service = $model;
            $plan = $service->plan;
            if (!$plan->is_synchronized) {
                return ActionResponse::danger('Este plan no se ha creado dentro de los PPP profile');
            }

            // Formatear los datos del plan y el servicio
            $formattedData = $this->planFormatter->formatPlanAndService($plan, $service);

            // Verificar si se debe crear una Simple Queue
            if (MikrotikConfigProvider::getSimpleQueueEnabled()) {
                $this->simpleQueueManager->createSimpleQueueFromFormattedData(
                    $formattedData['service_ip'],
                    $formattedData
                );
            }

            // Verificar si se debe crear un PPPoE
            if (MikrotikConfigProvider::getPppEnabled()) {
                $useIpPool = MikrotikConfigProvider::getIpPoolEnabled(); // Obtener si el pool de IP está activo
                $service = MikrotikConfigProvider::getServiceType();
                $password = MikrotikConfigProvider::getPasswordPPPSecret();

                // Si el pool de IP está activo, no enviar dirección IP
                $serviceIp = $useIpPool ? null : $formattedData['service_ip'];
                $profile = strtolower(str_replace(' ', '_', $formattedData['plan_name']));
                $this->pppoeManager->createPPPoEClient(
                    $formattedData['service_name'],
                    $password,  // Se puede ajustar
                    $service,             // Tipo de servicio
                    $profile,                // Perfil, si es necesario
                    $serviceIp // Dirección IP del servicio
                );
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
