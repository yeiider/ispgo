<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\HtmlTemplate;
use App\Models\Services\Service;
use App\Services\HtmlTemplateEngine;
use App\Settings\ServiceProviderConfig;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Http\Response;

class Signed extends Controller
{
    public function index($contractId): \Inertia\Response|Response
    {
        // Validar si los contratos están habilitados en la configuración
        if (!ServiceProviderConfig::enableContracts()) {
            return abort(404, 'Contracts are not enabled.');
        }

        // Verificar si el contrato existe
        $contract = Contract::find($contractId);
        if (!$contract) {
            return abort(404, "Contract with ID {$contractId} not found.");
        }

        // Verificar si el contrato posee un servicio asociado
        $service = $contract->service;
        if (!$service) {
            return abort(404, "Service not found for contract ID {$contractId}.");
        }

        // Obtener el ID de la plantilla desde la configuración
        $templateId = ServiceProviderConfig::contractTemplate();
        if (is_null($templateId)) {
            return abort(400, 'Contract template ID is not configured.');
        }

        // Construir el HTML de la plantilla
        try {
            $htmlTemplate = $this->htmlBuild($templateId, $service);
        } catch (\Exception $e) {
            // Manejar errores en la construcción del HTML
            return response()->json([
                'error' => 'Failed to generate contract template.',
                'message' => $e->getMessage(),
            ], 500);
        }

        // Verificar si el contrato ya ha sido firmado
        $isSigned = $contract->is_signed;
        // Puedes ajustar la lógica de la fecha de firma:
        // si tienes un campo signed_at, úsalo. De lo contrario, podría ser updated_at.
        $signedDate = $contract->signed_at ?? null;

        // Construir la URL del PDF firmado (si existe)
        // De acuerdo con tu lógica, lo guardas en: public/contracts/contract_{ID}_signed.pdf
        $pdfFilename = "contract_{$contractId}_signed.pdf";
        $pdfPath     = "public/contracts/{$pdfFilename}"; // Ruta interna en storage
        $pdfUrl      = null;

        // Si está firmado, armamos la url desde Storage
        if ($isSigned && Storage::exists($pdfPath)) {
            $pdfUrl = Storage::url($pdfPath);
        }

        return Inertia::render('Signed/Index', [
            "contractHtml" => $htmlTemplate,
            "url_signed"   => route('signed.signedContract', $contractId),

            // Props adicionales para manejar la firma
            "isSigned"     => $isSigned,
            "signedAt"     => $signedDate,
            "pdfUrl"       => $pdfUrl,
        ]);
    }

    private function htmlBuild(?int $templateId, Service $service)
    {
        $htmlTemplate = HtmlTemplate::find($templateId);
        if (!$htmlTemplate) {
            throw new \Exception("HTML template with ID {$templateId} not found.");
        }
        try {
            $engineTemplate = new HtmlTemplateEngine($htmlTemplate, ["service" => $service]);
            return $engineTemplate->renderContentOnly();
        } catch (\Exception $e) {
            throw new \Exception("Error processing HTML template: " . $e->getMessage());
        }
    }

    public function download($contractId): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return response()->download(storage_path('app/public/contracts/' . $contractId . '.pdf'));
    }

    public function signedContract(Request $request, $contractId): \Illuminate\Http\JsonResponse
    {
        $contract = Contract::find($contractId);
        if (!$contract) {
            return abort(404, "Contract with ID {$contractId} not found.");
        }

        // Si el contrato ya está firmado, puedes decidir qué hacer:
        if ($contract->is_signed) {
            // O puedes permitir firmarlo de nuevo, eso depende de tu lógica.
            // En este ejemplo devolvemos un mensaje que ya está firmado.
            return response()->json([
                'message' => 'Contract is already signed.',
                'pdf_url' => route('signed.download', $contractId), // o la URL de descarga, si gustas
            ], 200);
        }

        // Validar la solicitud y la firma
        $validated = $request->validate([
            'signature' => 'required|image|max:2048', // Solo permite imágenes de hasta 2MB
        ]);

        try {
            // Leer el contenido binario de la imagen recibida
            $signatureFile = $request->file('signature');
            $signatureData = file_get_contents($signatureFile->getRealPath());
            // Convertir a Base64
            $signatureBase64 = base64_encode($signatureData);
            // Construir un "data URI" para la imagen
            $signatureUri = 'data:image/png;base64,' . $signatureBase64;

            // Obtener la plantilla HTML del contrato
            $service = $contract->service;
            if (!$service) {
                return abort(404, "Service not found for contract ID {$contractId}.");
            }

            $templateId = ServiceProviderConfig::contractTemplate();
            $htmlTemplate = $this->htmlBuild($templateId, $service);

            // Renderizar el HTML con la firma incluida
            $htmlWithSignature = view('service/contract/contract_with_signature', [
                'content' => $htmlTemplate,
                'signatureUrl' => $signatureUri, // La imagen en data URI
                'customer' => [
                    'name' => $service->customer->full_name,
                    'document' => $service->customer->identity_document,
                ],
            ])->render();

            // Convertir el HTML a PDF usando DomPDF
            $pdf = PDF::loadHTML($htmlWithSignature)->setPaper('a4', 'portrait');

            // Guardar el PDF en almacenamiento local
            $pdfPath = "public/contracts/contract_{$contractId}_signed.pdf";
            Storage::put($pdfPath, $pdf->output());

            // Obtener la URL pública del PDF
            $pdfUrl = Storage::url($pdfPath);

            // Actualizar el contrato para marcarlo como firmado
            $contract->is_signed = true;
            $contract->signed_at = now();
            $contract->save();

            // Responder con éxito
            return response()->json([
                'message' => 'Contract signed and saved successfully.',
                'pdf_url' => asset($pdfUrl),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to sign the contract.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }




}
