<?php

namespace App\GraphQL\Mutations;

use App\Services\ContractService;
use Exception;
use Illuminate\Support\Arr;

class ContractMutation
{
    /**
     * @var ContractService
     */
    protected $contractService;

    /**
     * ContractMutation constructor.
     *
     * @param ContractService $contractService
     */
    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    /**
     * Create a contract.
     */
    public function create($_, array $args)
    {
        return $this->contractService->save($args);
    }

    /**
     * Update a contract.
     */
    public function update($_, array $args)
    {
        $id = Arr::pull($args, 'id');
        return $this->contractService->update($args, $id);
    }

    /**
     * Send contract signing link via email.
     */
    public function send($_, array $args)
    {
        try {
            $contract = $this->contractService->sendContract($args['id']);
            return [
                'success' => true,
                'message' => 'Contrato enviado al cliente exitosamente.',
                'contract' => $contract,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al enviar el contrato: ' . $e->getMessage(),
                'contract' => null,
            ];
        }
    }

    /**
     * Resend contract signing link via email.
     */
    public function resend($_, array $args)
    {
        try {
            $contract = $this->contractService->resendContract($args['id']);
            return [
                'success' => true,
                'message' => 'Contrato reenviado al cliente exitosamente.',
                'contract' => $contract,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al reenviar el contrato: ' . $e->getMessage(),
                'contract' => null,
            ];
        }
    }

    /**
     * Approve contract.
     */
    public function approve($_, array $args)
    {
        return $this->contractService->approveContract($args['id']);
    }

    /**
     * Reject contract.
     */
    public function reject($_, array $args)
    {
        return $this->contractService->rejectContract($args['id'], $args['reason']);
    }

    /**
     * Sign contract from customer side (moves S3 files and compiles final signed PDF).
     */
    public function sign($_, array $args)
    {
        try {
            $contract = $this->contractService->signContract(
                $args['id'],
                $args['signature_base64'],
                $args['cedula_temp_path'],
                $args['utility_bill_temp_path'] ?? null
            );
            return [
                'success' => true,
                'message' => 'Contrato firmado y cargado exitosamente.',
                'contract' => $contract,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al firmar el contrato: ' . $e->getMessage(),
                'contract' => null,
            ];
        }
    }
}
