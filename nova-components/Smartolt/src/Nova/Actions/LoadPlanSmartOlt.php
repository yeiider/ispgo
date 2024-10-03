<?php

namespace Ispgo\Smartolt\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Ispgo\Smartolt\Services\ApiManager;
use Ispgo\Smartolt\Settings\ProviderSmartOlt;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Actions\ActionResponse;

class LoadPlanSmartOlt extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection $models
     * @return ActionResponse
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if (!ProviderSmartOlt::getEnabled()) {
            return Action::danger('SmartOLT no está habilitado en la configuración.');
        }

        $successCount = 0;
        $errorMessages = [];

        foreach ($models as $model) {
            // Verificar que el modelo tenga un plan asociado
            if (!$model->plan) {
                $errorMessages[] = "El servicio con ID {$model->id} no tiene un plan asignado.";
                continue;
            }

            // Verificar que el plan tenga un perfil de SmartOLT
            $profileSmartOlt = $model->plan->profile_smart_olt;
            if (empty($profileSmartOlt)) {
                $errorMessages[] = "El plan del servicio con ID {$model->id} no tiene un perfil de SmartOLT definido.";
                continue;
            }

            // Dividir el perfil en partes
            $planParts = explode('/', $profileSmartOlt);
            if (count($planParts) !== 2) {
                $errorMessages[] = "El perfil de SmartOLT del plan del servicio con ID {$model->id} es inválido.";
                continue;
            }

            $uploadSpeedProfileName = $planParts[0];
            $downloadSpeedProfileName = $planParts[1];

            $payload = [
                "upload_speed_profile_name" => $uploadSpeedProfileName,
                "download_speed_profile_name" => $downloadSpeedProfileName,
            ];

            // Verificar que el servicio tenga un número de serie (SN)
            $sn = $model->sn;
            if (empty($sn)) {
                $errorMessages[] = "El servicio con ID {$model->id} no tiene un número de serie (SN) asignado.";
                continue;
            }

            $api = new ApiManager();

            try {
                $response = $api->updatePlan($sn, $payload);

                if ($response->successful()) {
                    $successCount++;
                } else {
                    $errorBody = $response->body();
                    $errorMessages[] = "No se pudo actualizar el plan para el servicio con ID {$model->id}: {$errorBody}";
                }
            } catch (ConnectionException $e) {
                $errorMessages[] = "Error de conexión al actualizar el plan para el servicio con ID {$model->id}: {$e->getMessage()}";
            } catch (\Exception $e) {
                $errorMessages[] = "Excepción al actualizar el plan para el servicio con ID {$model->id}: {$e->getMessage()}";
            }
        }

        if ($successCount > 0 && empty($errorMessages)) {
            return Action::message("Se actualizó el plan exitosamente para {$successCount} servicio(s).");
        } elseif ($successCount > 0 && !empty($errorMessages)) {
            $errorText = implode("\n", $errorMessages);
            return Action::danger("Se actualizaron {$successCount} servicio(s), pero ocurrieron algunos errores:\n{$errorText}");
        } else {
            $errorText = implode("\n", $errorMessages);
            return Action::danger("No se pudieron actualizar los planes:\n{$errorText}");
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
