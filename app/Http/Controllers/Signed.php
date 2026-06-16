<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\HtmlTemplate;
use App\Models\EmailTemplate;
use App\Models\Services\Service;
use App\Services\HtmlTemplateEngine;
use App\Settings\ServiceProviderConfig;
use App\Settings\GeneralProviderConfig;
use App\Mail\DynamicEmail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Http\Response;

class Signed extends Controller
{
    public function index($contractId): \Inertia\Response|Response
    {
        // Validar si los contratos están habilitados en la configuración
        if (!ServiceProviderConfig::enableContracts()) {
            abort(404, 'Contracts are not enabled.');
        }

        // Verificar si el contrato existe
        $contract = Contract::find($contractId);
        if (!$contract) {
            abort(404, "Contract with ID {$contractId} not found.");
        }

        // Verificar si el contrato posee un servicio asociado
        $service = $contract->service;
        if (!$service) {
            abort(404, "Service not found for contract ID {$contractId}.");
        }

        // Obtener el ID de la plantilla desde la configuración
        $templateId = ServiceProviderConfig::contractTemplate();
        if (is_null($templateId)) {
            abort(400, 'Contract template ID is not configured.');
        }

        // Construir el HTML de la plantilla
        try {
            $htmlTemplate = $this->htmlBuild($templateId, $service);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate contract template.',
                'message' => $e->getMessage(),
            ], 500);
        }

        // Verificar si el contrato ya ha sido firmado
        $isSigned = $contract->is_signed;
        $signedDate = $contract->signed_at ?? null;

        // Si está firmado, obtenemos la URL desde S3
        $pdfUrl = $isSigned ? $contract->contract_pdf_url : null;

        return Inertia::render('Signed/Index', [
            "contractHtml" => $htmlTemplate,
            "url_signed" => route('signed.signedContract', $contractId),
            "isSigned" => $isSigned,
            "signedAt" => $signedDate,
            "pdfUrl" => $pdfUrl
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

    public function download($contractId)
    {
        $contract = Contract::findOrFail($contractId);
        if (empty($contract->contract_pdf_path)) {
            abort(404, 'No PDF found for this contract.');
        }
        return redirect()->away($contract->contract_pdf_url);
    }

    public function signedContract(Request $request, $contractId): \Illuminate\Http\JsonResponse
    {
        $contract = Contract::find($contractId);
        if (!$contract) {
            return abort(404, "Contract with ID {$contractId} not found.");
        }

        if ($contract->status === 'approved') {
            return response()->json([
                'message' => 'Contract is already signed and approved.',
                'pdf_url' => $contract->contract_pdf_url,
            ], 200);
        }

        // Validar la solicitud, firma y documentos adjuntos
        $validated = $request->validate([
            'signature' => 'required|image|max:2048', // Solo permite imágenes de hasta 2MB
            'cedula' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Cédula obligatoria (hasta 5MB)
            'utility_bill' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Recibo opcional (hasta 5MB)
        ]);

        try {
            // Cargar cédula obligatoria a S3
            $cedulaFile = $request->file('cedula');
            $cedulaExt = $cedulaFile->getClientOriginalExtension();
            $cedulaPath = $cedulaFile->storeAs(
                'contracts/documents',
                "{$contractId}_cedula.{$cedulaExt}",
                's3'
            );

            // Cargar recibo opcional a S3 (si fue enviado)
            $utilityBillPath = null;
            if ($request->hasFile('utility_bill')) {
                $billFile = $request->file('utility_bill');
                $billExt = $billFile->getClientOriginalExtension();
                $utilityBillPath = $billFile->storeAs(
                    'contracts/documents',
                    "{$contractId}_recibo.{$billExt}",
                    's3'
                );
            }

            // Leer el contenido binario de la firma y convertir a Base64
            $signatureFile = $request->file('signature');
            $signatureData = file_get_contents($signatureFile->getRealPath());
            $signatureBase64 = base64_encode($signatureData);
            $signatureUri = 'data:image/png;base64,' . $signatureBase64;

            // Obtener el servicio y la plantilla HTML
            $service = $contract->service;
            if (!$service) {
                return abort(404, "Service not found for contract ID {$contractId}.");
            }

            $templateId = ServiceProviderConfig::contractTemplate();
            $htmlTemplate = $this->htmlBuild($templateId, $service);

            // Cargar la firma del representante
            $representativeSignatureUri = null;
            $signaturePath = ServiceProviderConfig::representativeSignature();

            if (!empty($signaturePath)) {
                if (str_starts_with($signaturePath, '/storage')) {
                    $relativePath = str_replace('/storage', '', $signaturePath);
                    $fullPath = storage_path('app/public' . $relativePath);
                } else {
                    $fullPath = storage_path('app/public/' . ltrim($signaturePath, '/'));
                }
                if (file_exists($fullPath)) {
                    $imageContent = file_get_contents($fullPath);
                    $mimeType = mime_content_type($fullPath);
                    $representativeSignatureUri = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
                }
            }

            // Unir el HTML con firmas
            $htmlWithSignature = view('service/contract/contract_with_signature', [
                'content' => $htmlTemplate,
                'signatureUrl' => $signatureUri,
                'customer' => [
                    'name' => $service->customer->full_name,
                    'document' => $service->customer->identity_document,
                ],
                "representativeSignature" => $representativeSignatureUri,
                "representativeName" => ServiceProviderConfig::representativeName(),
                "representativeDocument" => ServiceProviderConfig::representativeDocument(),
                "representativeRole" => ServiceProviderConfig::representativeRole(),
            ])->render();

            // Convertir el HTML a PDF usando DomPDF
            $pdf = PDF::loadHTML($htmlWithSignature)->setPaper('a4', 'portrait');

            // Guardar el PDF firmado en S3
            $pdfPath = "contracts/signed/contract_{$contractId}_signed.pdf";
            Storage::disk('s3')->put($pdfPath, $pdf->output());

            // Actualizar el contrato
            $contract->is_signed = true;
            $contract->signed_at = now();
            $contract->contract_pdf_path = $pdfPath;
            $contract->cedula_path = $cedulaPath;
            $contract->utility_bill_path = $utilityBillPath;
            $contract->status = 'signed';
            $contract->save();

            // Enviar correo de notificación de firma
            $emailTemplateId = ServiceProviderConfig::emailTemplateSigned();
            if ($emailTemplateId) {
                try {
                    $emailTemplate = EmailTemplate::find($emailTemplateId);
                    if ($emailTemplate) {
                        $customer = $contract->customer;
                        $logo = GeneralProviderConfig::getCompanyLogo();
                        $img_header = $logo ? asset('storage/' . $logo) : null;
                        
                        Mail::to($customer->email_address)
                            ->send(new DynamicEmail(['contract' => $contract], $emailTemplate, $img_header));
                    }
                } catch (\Exception $e) {
                    report($e);
                }
            }

            return response()->json([
                'message' => 'Contract signed and saved successfully.',
                'pdf_url' => $contract->contract_pdf_url,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to sign the contract.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
