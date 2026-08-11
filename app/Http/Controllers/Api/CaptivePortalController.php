<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customers\Customer;
use App\Models\GuestWifiAccess;
use App\Models\Services\Service;
use App\Notifications\OtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CaptivePortalController extends Controller
{
    /**
     * Verifica si un usuario es cliente y tiene servicios activos, o si es invitado con acceso válido
     * Si es cliente activo o no existe, envía un OTP
     */
    public function requestAccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity_document' => 'required_without:email|string',
            'email' => 'required_without:identity_document|email',
            'full_name' => 'required_if:is_guest,true|string',
            'phone_number' => 'required_if:is_guest,true|string|max:15',
            'router_id' => 'required|exists:routers,id',
            'otp_method' => 'required|in:email,whatsapp',
            'mac_address' => 'nullable|string|max:17',
            'ip_address' => 'nullable|ip',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $routerId = $request->input('router_id');
        $otpMethod = $request->input('otp_method');

        // Caso 1: Buscar cliente existente por cédula
        if ($request->has('identity_document')) {
            $customer = Customer::where('identity_document', $request->input('identity_document'))
                ->where('router_id', $routerId)
                ->where('customer_status', 'active')
                ->first();

            if ($customer) {
                return $this->handleExistingCustomer($customer, $routerId, $otpMethod);
            }
        }

        // Caso 2: Cliente no existe, manejar como invitado
        return $this->handleGuest($request, $routerId, $otpMethod);
    }

    /**
     * Maneja el acceso para clientes existentes
     */
    private function handleExistingCustomer(Customer $customer, int $routerId, string $otpMethod)
    {
        // Verificar que tenga al menos un servicio activo
        $activeServices = Service::where('customer_id', $customer->id)
            ->where('service_status', 'active')
            ->count();

        if ($activeServices === 0) {
            return response()->json([
                'success' => false,
                'message' => 'El cliente no tiene servicios activos.'
            ], 403);
        }

        // Verificar que no tenga facturas vencidas
        $unpaidInvoices = $customer->invoices()
            ->where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->count();

        if ($unpaidInvoices > 0) {
            return response()->json([
                'success' => false,
                'message' => 'El cliente tiene facturas vencidas pendientes de pago.'
            ], 403);
        }

        // Enviar OTP al cliente
        $otpCode = GuestWifiAccess::generateOtpCode();

        $guestAccess = GuestWifiAccess::create([
            'full_name' => $customer->full_name,
            'phone_number' => $customer->phone_number,
            'email' => $customer->email_address,
            'router_id' => $routerId,
            'otp_code' => $otpCode,
            'otp_method' => $otpMethod,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $this->sendOtp($customer->email_address, $customer->phone_number, $otpCode, $otpMethod);

        return response()->json([
            'success' => true,
            'message' => 'Código OTP enviado exitosamente.',
            'access_id' => $guestAccess->id,
            'otp_method' => $otpMethod,
            'expires_at' => $guestAccess->otp_expires_at,
        ]);
    }

    /**
     * Maneja el acceso para usuarios invitados (no clientes)
     */
    private function handleGuest(Request $request, int $routerId, string $otpMethod)
    {
        $email = $request->input('email');
        $phone = $request->input('phone_number');

        // Verificar si ya tiene acceso válido en las últimas 24 horas
        $existingAccess = null;

        if ($email) {
            $existingAccess = GuestWifiAccess::findActiveByEmail($email, $routerId);
        } elseif ($phone) {
            $existingAccess = GuestWifiAccess::findActiveByPhone($phone, $routerId);
        }

        if ($existingAccess) {
            return response()->json([
                'success' => true,
                'message' => 'Ya tienes acceso activo.',
                'access_valid_until' => $existingAccess->access_expires_at,
            ]);
        }

        // Crear nuevo registro de invitado y enviar OTP
        $otpCode = GuestWifiAccess::generateOtpCode();

        $guestAccess = GuestWifiAccess::create([
            'full_name' => $request->input('full_name'),
            'phone_number' => $phone,
            'email' => $email,
            'router_id' => $routerId,
            'otp_code' => $otpCode,
            'otp_method' => $otpMethod,
            'otp_expires_at' => now()->addMinutes(10),
            'mac_address' => $request->input('mac_address'),
            'ip_address' => $request->input('ip_address'),
        ]);

        $this->sendOtp($email, $phone, $otpCode, $otpMethod);

        return response()->json([
            'success' => true,
            'message' => 'Código OTP enviado exitosamente.',
            'access_id' => $guestAccess->id,
            'otp_method' => $otpMethod,
            'expires_at' => $guestAccess->otp_expires_at,
        ]);
    }

    /**
     * Verifica el código OTP y otorga acceso
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_id' => 'required|exists:guest_wifi_access,id',
            'otp_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $guestAccess = GuestWifiAccess::find($request->input('access_id'));

        if (!$guestAccess->isOtpValid($request->input('otp_code'))) {
            return response()->json([
                'success' => false,
                'message' => 'Código OTP inválido o expirado.'
            ], 401);
        }

        $guestAccess->markAsVerified();

        return response()->json([
            'success' => true,
            'message' => 'Acceso concedido exitosamente.',
            'access_valid_until' => $guestAccess->access_expires_at,
        ]);
    }

    /**
     * Verifica si el usuario tiene acceso activo
     */
    public function checkAccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone_number|email',
            'phone_number' => 'required_without:email|string',
            'router_id' => 'required|exists:routers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $routerId = $request->input('router_id');
        $existingAccess = null;

        if ($request->has('email')) {
            $existingAccess = GuestWifiAccess::findActiveByEmail($request->input('email'), $routerId);
        } else {
            $existingAccess = GuestWifiAccess::findActiveByPhone($request->input('phone_number'), $routerId);
        }

        if ($existingAccess && $existingAccess->hasActiveAccess()) {
            return response()->json([
                'success' => true,
                'has_access' => true,
                'access_valid_until' => $existingAccess->access_expires_at,
            ]);
        }

        return response()->json([
            'success' => true,
            'has_access' => false,
        ]);
    }

    /**
     * Envía el código OTP por el método seleccionado
     */
    private function sendOtp(?string $email, ?string $phone, string $otpCode, string $method)
    {
        if ($method === 'email' && $email) {
            // Enviar por email usando el sistema de notificaciones de Laravel
            try {
                \Illuminate\Support\Facades\Mail::raw(
                    "Tu código de acceso WiFi es: {$otpCode}\n\nEste código expira en 10 minutos.",
                    function ($message) use ($email) {
                        $message->to($email)
                            ->subject('Código de Acceso WiFi');
                    }
                );
            } catch (\Exception $e) {
                \Log::error('Error enviando OTP por email: ' . $e->getMessage());
            }
        } elseif ($method === 'whatsapp' && $phone) {
            // TODO: Implementar envío por WhatsApp cuando esté disponible
            \Log::info("OTP por WhatsApp al {$phone}: {$otpCode}");
        }
    }
}
