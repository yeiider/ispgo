<?php
namespace App\Services;

use App\Models\Contract;
use App\Repositories\ContractRepository;
use App\Models\EmailTemplate;
use App\Mail\DynamicEmail;
use App\Settings\ServiceProviderConfig;
use App\Settings\GeneralProviderConfig;
use App\GraphQL\Mutations\FileUploadMutation;
use App\Services\HtmlTemplateEngine;
use App\Models\HtmlTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use InvalidArgumentException;

class ContractService
{
    /**
     * @var ContractRepository $contractRepository
     */
    protected $contractRepository;

    /**
     * ContractService constructor.
     *
     * @param ContractRepository $contractRepository
     */
    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    /**
     * Get all contracts.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->contractRepository->all();
    }

    /**
     * Get contract by id.
     *
     * @param string $id
     * @return Contract
     */
    public function getById(string $id)
    {
        return $this->contractRepository->getById($id);
    }

    /**
     * Validate and save contract data.
     *
     * @param array $data
     * @return Contract
     */
    public function save(array $data)
    {
        $data['status'] = 'draft';
        $data['is_signed'] = false;
        return $this->contractRepository->save($data);
    }

    /**
     * Update contract data.
     *
     * @param array $data
     * @param string $id
     * @return Contract
     */
    public function update(array $data, string $id)
    {
        DB::beginTransaction();
        try {
            $contract = $this->contractRepository->update($data, $id);
            DB::commit();
            return $contract;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update contract data: ' . $e->getMessage());
        }
    }

    /**
     * Delete contract by id.
     *
     * @param string $id
     * @return Contract
     */
    public function deleteById(string $id)
    {
        DB::beginTransaction();
        try {
            $contract = $this->contractRepository->delete($id);
            DB::commit();
            return $contract;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete contract data: ' . $e->getMessage());
        }
    }

    /**
     * Send contract link to customer.
     *
     * @param string $id
     * @return Contract
     * @throws Exception
     */
    public function sendContract(string $id)
    {
        $contract = Contract::findOrFail($id);

        $templateId = ServiceProviderConfig::emailTemplateSend();
        if (!$templateId) {
            throw new Exception("El template de correo de envío de contrato no está configurado.");
        }

        $emailTemplate = EmailTemplate::find($templateId);
        if (!$emailTemplate) {
            throw new Exception("La plantilla de correo con ID {$templateId} no existe.");
        }

        $customer = $contract->customer;
        if (!$customer || empty($customer->email_address)) {
            throw new Exception("El cliente asociado no tiene dirección de correo electrónico.");
        }

        $signatureUrl = route('signed', ['contractId' => $contract->id]);

        $logo = GeneralProviderConfig::getCompanyLogo();
        $img_header = $logo ? asset('storage/' . $logo) : null;

        $data = [
            'contract' => $contract,
            'url_signature' => $signatureUrl,
        ];

        Mail::to($customer->email_address)
            ->send(new DynamicEmail($data, $emailTemplate, $img_header));

        $contract->status = 'sent';
        $contract->save();

        return $contract;
    }

    /**
     * Resend contract link to customer.
     *
     * @param string $id
     * @return Contract
     */
    public function resendContract(string $id)
    {
        return $this->sendContract($id);
    }

    /**
     * Process contract signing from customer side.
     *
     * @param string $id
     * @param string $signatureBase64
     * @param string $cedulaTempPath
     * @param string|null $utilityBillTempPath
     * @return Contract
     * @throws Exception
     */
    public function signContract(string $id, string $signatureBase64, string $cedulaTempPath, ?string $utilityBillTempPath = null)
    {
        $contract = Contract::findOrFail($id);

        if ($contract->status === 'approved') {
            throw new Exception("Este contrato ya ha sido firmado y aprobado.");
        }

        // Validate temporary files
        if (!FileUploadMutation::validateTempPath($cedulaTempPath)) {
            throw new Exception("El archivo temporal de la cédula no existe o expiró.");
        }

        if (!empty($utilityBillTempPath) && !FileUploadMutation::validateTempPath($utilityBillTempPath)) {
            throw new Exception("El archivo temporal del recibo no existe o expiró.");
        }

        // Move files to permanent S3 storage
        $cedulaExt = pathinfo($cedulaTempPath, PATHINFO_EXTENSION) ?: 'png';
        $cedulaName = "{$contract->id}_cedula.{$cedulaExt}";
        $cedulaConfirm = FileUploadMutation::moveToPermanentStorage($cedulaTempPath, 'contracts/documents', $cedulaName);
        
        if (!$cedulaConfirm['success']) {
            throw new Exception("Error al mover la cédula a S3: " . ($cedulaConfirm['message'] ?? ''));
        }
        $cedulaPath = $cedulaConfirm['permanent_path'];

        $utilityBillPath = null;
        if (!empty($utilityBillTempPath)) {
            $billExt = pathinfo($utilityBillTempPath, PATHINFO_EXTENSION) ?: 'png';
            $billName = "{$contract->id}_recibo.{$billExt}";
            $billConfirm = FileUploadMutation::moveToPermanentStorage($utilityBillTempPath, 'contracts/documents', $billName);
            if ($billConfirm['success']) {
                $utilityBillPath = $billConfirm['permanent_path'];
            }
        }

        // Decode signature image
        $signatureData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $signatureBase64));
        $signatureUri = 'data:image/png;base64,' . base64_encode($signatureData);

        // Save client signature permanently to S3/Storage
        $clientSignaturePath = "contracts/documents/{$contract->id}_signature.png";
        Storage::disk('s3')->put($clientSignaturePath, $signatureData);

        // Generate contract PDF (Client-only signature for now)
        $pdfPath = $this->generateContractPdf($contract, $signatureUri, false);

        // Update contract status
        $contract->is_signed = true;
        $contract->signed_at = now();
        $contract->contract_pdf_path = $pdfPath;
        $contract->cedula_path = $cedulaPath;
        $contract->utility_bill_path = $utilityBillPath;
        $contract->status = 'signed';
        $contract->save();

        // Notify customer that contract is signed and pending review
        $emailTemplateId = ServiceProviderConfig::emailTemplateSigned();
        if ($emailTemplateId) {
            try {
                $emailTemplate = EmailTemplate::find($emailTemplateId);
                if ($emailTemplate) {
                    $customer = $contract->customer;
                    $logo = GeneralProviderConfig::getCompanyLogo();
                    $img_header = $logo ? asset('storage/' . $logo) : null;
                    $emailData = ['contract' => $contract];
                    Mail::to($customer->email_address)
                        ->send(new DynamicEmail($emailData, $emailTemplate, $img_header));
                }
            } catch (Exception $e) {
                // Log and ignore to prevent failure of signing process if email fails
                report($e);
            }
        }

        return $contract;
    }

    /**
     * Generate contract PDF with optional representative signature.
     *
     * @param Contract $contract
     * @param string $clientSignatureUri
     * @param bool $includeRepresentative
     * @return string
     * @throws Exception
     */
    public function generateContractPdf(Contract $contract, string $clientSignatureUri, bool $includeRepresentative = false)
    {
        $service = $contract->service;
        if (!$service) {
            throw new Exception("El servicio asociado al contrato no fue encontrado.");
        }

        $htmlTemplateId = ServiceProviderConfig::contractTemplate();
        if (is_null($htmlTemplateId)) {
            throw new Exception("La plantilla HTML del contrato no está configurada.");
        }

        $htmlTemplate = HtmlTemplate::find($htmlTemplateId);
        if (!$htmlTemplate) {
            throw new Exception("La plantilla HTML con ID {$htmlTemplateId} no fue encontrada.");
        }

        $engineTemplate = new HtmlTemplateEngine($htmlTemplate, ["contract" => $contract, "service" => $service]);
        $htmlContent = $engineTemplate->renderContentOnly();

        $representativeSignatureUri = null;
        $representativeName = '';
        $representativeDocument = '';
        $representativeRole = '';

        if ($includeRepresentative) {
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
            $representativeName = ServiceProviderConfig::representativeName();
            $representativeDocument = ServiceProviderConfig::representativeDocument();
            $representativeRole = ServiceProviderConfig::representativeRole();
        }

        $htmlWithSignature = view('service/contract/contract_with_signature', [
            'content' => $htmlContent,
            'signatureUrl' => $clientSignatureUri,
            'customer' => [
                'name' => $service->customer->full_name,
                'document' => $service->customer->identity_document,
            ],
            "representativeSignature" => $representativeSignatureUri,
            "representativeName" => $representativeName,
            "representativeDocument" => $representativeDocument,
            "representativeRole" => $representativeRole,
        ])->render();

        $pdf = Pdf::loadHTML($htmlWithSignature)->setPaper('a4', 'portrait');
        $pdfPath = "contracts/signed/contract_{$contract->id}_signed.pdf";
        Storage::disk('s3')->put($pdfPath, $pdf->output());

        return $pdfPath;
    }

    /**
     * Approve contract.
     *
     * @param string $id
     * @return Contract
     */
    public function approveContract(string $id)
    {
        $contract = Contract::findOrFail($id);

        // Regenerate contract PDF with representative signature and details
        try {
            $clientSignaturePath = "contracts/documents/{$contract->id}_signature.png";
            $clientSignatureUri = null;
            if (Storage::disk('s3')->exists($clientSignaturePath)) {
                $signatureData = Storage::disk('s3')->get($clientSignaturePath);
                $clientSignatureUri = 'data:image/png;base64,' . base64_encode($signatureData);
            }

            if ($clientSignatureUri) {
                $this->generateContractPdf($contract, $clientSignatureUri, true);
            }
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error regenerating contract PDF on approval: ' . $e->getMessage());
            throw $e;
        }

        $contract->status = 'approved';
        $contract->save();

        // Notify client with the approved email template
        $emailTemplateId = ServiceProviderConfig::emailTemplateApproved();
        if ($emailTemplateId) {
            $emailTemplate = EmailTemplate::find($emailTemplateId);
            if ($emailTemplate) {
                $customer = $contract->customer;
                $logo = GeneralProviderConfig::getCompanyLogo();
                $img_header = $logo ? asset('storage/' . $logo) : null;
                $emailData = [
                    'contract' => $contract,
                    'download_contract_url' => $contract->contract_pdf_url,
                ];

                Mail::to($customer->email_address)
                    ->send(new DynamicEmail($emailData, $emailTemplate, $img_header));
            }
        }

        return $contract;
    }

    /**
     * Reject contract.
     *
     * @param string $id
     * @param string $reason
     * @return Contract
     */
    public function rejectContract(string $id, string $reason)
    {
        $contract = Contract::findOrFail($id);
        $contract->status = 'rejected';
        $contract->save();

        // Notify client with the rejection reason
        $emailTemplateId = ServiceProviderConfig::emailTemplateRejected();
        if ($emailTemplateId) {
            $emailTemplate = EmailTemplate::find($emailTemplateId);
            if ($emailTemplate) {
                $customer = $contract->customer;
                $logo = GeneralProviderConfig::getCompanyLogo();
                $img_header = $logo ? asset('storage/' . $logo) : null;
                $signatureUrl = route('signed', ['contractId' => $contract->id]);

                $emailData = [
                    'contract' => $contract,
                    'reason' => $reason,
                    'url_signature' => $signatureUrl,
                ];

                Mail::to($customer->email_address)
                    ->send(new DynamicEmail($emailData, $emailTemplate, $img_header));
            }
        }

        return $contract;
    }
}
