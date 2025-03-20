<?php

namespace App\Console\Commands;

use App\Models\Customers\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Ispgo\Smartolt\Services\ApiManager;
use Symfony\Component\Console\Command\Command as CommandAlias;

class SyncronizarDatosOnu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:syncronizar-datos-onu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza los datos de las ONUs con los servicios de los clientes.';

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle()
    {
        $this->info("Iniciando sincronización de datos ONUs...");

        // Instanciamos el gestor de la API
        $apiManager = new ApiManager();

        try {
            $response = $apiManager->getAllOnus();

            // Validamos la respuesta de la API
            if ($response->failed()) {
                $this->error("La solicitud a la API falló: " . $response->body());
                Log::error("Error al consultar ONUs desde la API", ['response' => $response->body()]);
                return CommandAlias::FAILURE;
            }

            $onus = $response->json();

            if (!isset($onus['onus']) || !is_array($onus['onus'])) {
                $this->error("La respuesta de la API no contiene un formato válido.");
                Log::error("Respuesta inválida al obtener ONUs desde la API", ['response' => $response]);
                return CommandAlias::FAILURE;
            }

            foreach ($onus["onus"] as $onu) {
                if (!isset($onu["name"], $onu["sn"])) {
                    $this->warn("Información incompleta de la ONU: " . json_encode($onu));
                    continue;
                }

                $onuName = $onu["name"];
                $onuSerial = $onu["sn"];

                try {
                    // Buscamos el cliente por su identificación
                    $customer = Customer::findByIdentityDocument($onuName);

                    if ($customer) {
                        $service = $customer->services->first();

                        if ($service) {
                            $service->sn = $onuSerial;
                            $service->save();

                            $this->info("Servicio actualizado para el cliente con documento: {$onuName}");
                        } else {
                            $this->warn("El cliente con documento {$onuName} no tiene un servicio asociado.");
                        }
                    } else {
                        $this->warn("Cliente no encontrado con documento: {$onuName}");
                    }
                } catch (\Exception $e) {
                    $this->error("Error procesando la ONU {$onuName}: " . $e->getMessage());
                    Log::error("Error al procesar ONU", ['onu' => $onu, 'exception' => $e]);
                }
            }

            $this->info("Sincronización completada con éxito.");
            return CommandAlias::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Se produjo un error al conectar con la API: " . $e->getMessage());
            Log::error("Error crítico durante la sincronización de ONUs", ['exception' => $e]);
            return CommandAlias::FAILURE;
        }
    }
}
