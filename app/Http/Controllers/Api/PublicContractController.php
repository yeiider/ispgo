<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\FileUploadController;
use App\Models\Contract;
use App\Models\HtmlTemplate;
use App\Services\ContractService;
use App\Services\HtmlTemplateEngine;
use App\Settings\ServiceProviderConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicContractController extends Controller
{
    protected ContractService $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * Get contract data for the public signing page.
     * Returns only the data needed for the signing UX (no sensitive fields).
     */
    public function show(string $id): JsonResponse
    {
        $contract = Contract::with(['customer', 'service.plan'])->find($id);

        if (!$contract) {
            return response()->json(['error' => 'Contrato no encontrado'], 404);
        }

        return response()->json([
            'id' => $contract->id,
            'status' => $contract->status,
            'start_date' => $contract->start_date?->toDateString(),
            'end_date' => $contract->end_date?->toDateString(),
            'is_signed' => $contract->is_signed,
            'signed_at' => $contract->signed_at?->toIso8601String(),
            'contract_pdf_url' => $contract->contract_pdf_url,
            'customer' => $contract->customer ? [
                'first_name' => $contract->customer->first_name,
                'last_name' => $contract->customer->last_name,
            ] : null,
            'service' => $contract->service ? [
                'id' => $contract->service->id,
                'plan' => $contract->service->plan ? [
                    'name' => $contract->service->plan->name,
                    'monthly_price' => $contract->service->plan->monthly_price,
                ] : null,
            ] : null,
        ]);
    }

    /**
     * Upload a temporary file for the public signing page.
     * Accepts images AND PDFs (unlike the auth-protected endpoint which only accepts images).
     */
    public function uploadTemp(Request $request): JsonResponse
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:10240', // 10MB
                'mimes:jpeg,jpg,png,gif,webp,pdf',
            ],
        ], [
            'file.required' => 'El archivo es requerido.',
            'file.max' => 'El archivo no puede exceder 10MB.',
            'file.mimes' => 'El archivo debe ser de tipo: jpeg, jpg, png, gif, webp o pdf.',
        ]);

        try {
            $file = $request->file('file');

            // Generate unique file name
            $extension = strtolower($file->getClientOriginalExtension());
            $uniqueName = Str::uuid() . '_' . time() . '.' . $extension;
            $fullPath = 'tmp/' . $uniqueName;

            // Read and store in S3
            $fileContent = file_get_contents($file->getRealPath());
            if ($fileContent === false) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error al leer el archivo.',
                ], 500);
            }

            $stored = Storage::disk('s3')->put($fullPath, $fileContent);
            if (!$stored) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error al cargar el archivo.',
                ], 500);
            }

            // Generate URL
            $useSignedUrls = config('filesystems.disks.s3.use_signed_urls', false);
            $url = $useSignedUrls
                ? Storage::disk('s3')->temporaryUrl($fullPath, now()->addMinutes(60))
                : Storage::disk('s3')->url($fullPath);

            return response()->json([
                'success' => true,
                'preview_url' => $url,
                'temp_path' => $fullPath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        } catch (\Exception $e) {
            Log::error('Public upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al subir el archivo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sign the contract from the public page.
     */
    public function sign(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'signature_base64' => 'required|string',
            'cedula_temp_path' => 'required|string',
            'utility_bill_temp_path' => 'nullable|string',
        ]);

        $contract = Contract::find($id);

        if (!$contract) {
            return response()->json(['success' => false, 'message' => 'Contrato no encontrado'], 404);
        }

        if (in_array($contract->status, ['approved', 'rejected'])) {
            return response()->json([
                'success' => false,
                'message' => 'Este contrato ya no permite cambios.',
            ], 422);
        }

        if ($contract->is_signed) {
            return response()->json([
                'success' => false,
                'message' => 'Este contrato ya fue firmado.',
            ], 422);
        }

        try {
            $contract = $this->contractService->signContract(
                $id,
                $request->input('signature_base64'),
                $request->input('cedula_temp_path'),
                $request->input('utility_bill_temp_path')
            );

            return response()->json([
                'success' => true,
                'message' => 'Contrato firmado y cargado exitosamente.',
                'contract' => [
                    'id' => $contract->id,
                    'status' => $contract->status,
                    'is_signed' => $contract->is_signed,
                    'signed_at' => $contract->signed_at?->toIso8601String(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Public contract sign error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Preview the rendered contract HTML (template with variables replaced).
     */
    public function preview(string $id): JsonResponse
    {
        $contract = Contract::with(['customer', 'service.plan', 'service.customer'])->find($id);

        if (!$contract) {
            return response()->json(['error' => 'Contrato no encontrado'], 404);
        }

        $service = $contract->service;
        if (!$service) {
            return response()->json(['error' => 'Servicio asociado no encontrado'], 404);
        }

        $htmlTemplateId = ServiceProviderConfig::contractTemplate();
        if (is_null($htmlTemplateId) || $htmlTemplateId === 0) {
            return response()->json(['error' => 'La plantilla de contrato no está configurada'], 404);
        }

        $htmlTemplate = HtmlTemplate::find($htmlTemplateId);
        if (!$htmlTemplate) {
            return response()->json(['error' => 'Plantilla no encontrada'], 404);
        }

        try {
            $engine = new HtmlTemplateEngine($htmlTemplate, ['contract' => $contract, 'service' => $service]);
            $html = $engine->renderContentOnly();

            return response()->json([
                'html' => $html,
                'styles' => $htmlTemplate->styles ?? '',
            ]);
        } catch (\Exception $e) {
            Log::error('Contract preview error: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar la vista previa del contrato'], 500);
        }
    }

    /**
     * Preview the contract as a streamed PDF.
     */
    public function previewPdf(string $id)
    {
        $contract = Contract::with(['customer', 'service.plan', 'service.customer'])->find($id);

        if (!$contract) {
            abort(404, 'Contrato no encontrado');
        }

        $service = $contract->service;
        if (!$service) {
            abort(404, 'Servicio no encontrado');
        }

        $htmlTemplateId = ServiceProviderConfig::contractTemplate();
        if (is_null($htmlTemplateId) || $htmlTemplateId === 0) {
            abort(404, 'La plantilla de contrato no está configurada');
        }

        $htmlTemplate = HtmlTemplate::find($htmlTemplateId);
        if (!$htmlTemplate) {
            abort(404, 'Plantilla no encontrada');
        }

        try {
            $engine = new HtmlTemplateEngine($htmlTemplate, ['contract' => $contract, 'service' => $service]);
            $htmlContent = $engine->renderContentOnly();

            // Representative signature configuration
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

            $htmlWithSignature = view('service/contract/contract_with_signature', [
                'content' => $htmlContent,
                'signatureUrl' => null, // Empty for preview since customer hasn't signed yet
                'customer' => [
                    'name' => $service->customer->full_name,
                    'document' => $service->customer->identity_document,
                ],
                "representativeSignature" => $representativeSignatureUri,
                "representativeName" => ServiceProviderConfig::representativeName(),
                "representativeDocument" => ServiceProviderConfig::representativeDocument(),
                "representativeRole" => ServiceProviderConfig::representativeRole(),
            ])->render();

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($htmlWithSignature)->setPaper('a4', 'portrait');

            return $pdf->stream("contract_{$contract->id}_preview.pdf");
        } catch (\Exception $e) {
            Log::error('Contract PDF preview error: ' . $e->getMessage());
            abort(500, 'Error al generar la vista previa del contrato en PDF');
        }
    }
}
