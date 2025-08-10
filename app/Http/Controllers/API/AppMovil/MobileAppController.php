<?php

namespace App\Http\Controllers\API\AppMovil;

use App\Http\Controllers\Controller;
use App\Models\Inventory\EquipmentAssignment;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Mobile App",
 *     description="API Endpoints for mobile technical support app"
 * )
 */
class MobileAppController extends Controller
{
    /**
     * Get services and customers from tickets assigned to authenticated user.
     *
     * @OA\Get(
     *     path="/api/v1/app-movil/tickets-data",
     *     summary="Get services and customers from authenticated user's tickets",
     *     tags={"Mobile App"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Services and customers retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="services",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Internet Service"),
     *                     @OA\Property(property="description", type="string", example="High-speed internet connection"),
     *                     @OA\Property(property="price", type="number", format="float", example=29.99),
     *                     @OA\Property(property="status", type="string", example="active")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="customers",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="address", type="string", example="123 Main St")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - User not authenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function getTicketsData(): JsonResponse
    {
        try {
            // Get tickets assigned to authenticated user
            $tickets = Ticket::forAuthenticatedUser();

            // Extract unique services from tickets
            $services = $tickets->map(function ($ticket) {
                return $ticket->service;
            })->filter()->unique('id')->values();

            // Extract unique customers from tickets
            $customers = $tickets->map(function ($ticket) {
                return $ticket->customer;
            })->filter()->unique('id')->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'services' => $services,
                    'customers' => $customers
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving tickets data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get only services from tickets assigned to authenticated user.
     *
     * @OA\Get(
     *     path="/api/v1/app-movil/services",
     *     summary="Get services from authenticated user's tickets",
     *     tags={"Mobile App"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Services retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="services",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Internet Service"),
     *                     @OA\Property(property="description", type="string", example="High-speed internet connection"),
     *                     @OA\Property(property="price", type="number", format="float", example=29.99),
     *                     @OA\Property(property="status", type="string", example="active")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - User not authenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function getServices(): JsonResponse
    {
        try {
            // Get tickets assigned to authenticated user
            $tickets = Ticket::forAuthenticatedUser();

            // Extract unique services from tickets
            $services = $tickets->map(function ($ticket) {
                $customer = $ticket->service->customer;
                return $ticket->service;
            })->filter()->unique('id')->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'services' => $services
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving services',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get only customers from tickets assigned to authenticated user.
     *
     * @OA\Get(
     *     path="/api/v1/app-movil/customers",
     *     summary="Get customers from authenticated user's tickets",
     *     tags={"Mobile App"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Customers retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="customers",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="phone", type="string", example="+1234567890"),
     *                     @OA\Property(property="address", type="string", example="123 Main St")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - User not authenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function getCustomers(): JsonResponse
    {
        try {
            // Get tickets assigned to authenticated user
            $tickets = Ticket::forAuthenticatedUser();

            // Extract unique customers from tickets
            $customers = $tickets->map(function ($ticket) {
                $address = $ticket->customer->addresses;
                return $ticket->customer;
            })->filter()->unique('id')->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'customers' => $customers
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving customers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Update partial fields of a service for Mobile App.
     *
     * @OA\Patch(
     *     path="/api/v1/app-movil/services/{service}/update-fields",
     *     summary="Actualizar campos parciales del servicio",
     *     tags={"Mobile App"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *          name="service",
     *          in="path",
     *          required=true,
     *          description="ID del servicio a actualizar",
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *          required=false,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="service_ip", type="string", example="192.168.1.10"),
     *              @OA\Property(property="username_router", type="string", example="admin"),
     *              @OA\Property(property="password_router", type="string", example="secret"),
     *              @OA\Property(property="mac_address", type="string", example="AA:BB:CC:DD:EE:FF"),
     *              @OA\Property(property="sn", type="string", example="SN123456"),
     *              @OA\Property(property="unu_latitude", type="number", format="float", example=4.710989),
     *              @OA\Property(property="unu_longitude", type="number", format="float", example=-74.072090)
     *          )
     *     ),
     *     @OA\Response(response=200, description="Servicio actualizado correctamente"),
     *     @OA\Response(response=404, description="Servicio no encontrado"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function updateServiceFields(\Illuminate\Http\Request $request, int $service): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validate([
                'service_ip' => ['sometimes', 'nullable', 'ip'],
                'username_router' => ['sometimes', 'nullable', 'string', 'max:255'],
                'password_router' => ['sometimes', 'nullable', 'string', 'max:255'],
                'mac_address' => ['sometimes', 'nullable', 'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/'],
                'sn' => ['sometimes', 'nullable', 'string', 'max:255'],
                'unu_latitude' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
                'unu_longitude' => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],
            ]);

            if (empty($validated)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay campos proporcionados para actualizar'
                ], 422);
            }

            $model = \App\Models\Services\Service::findOrFail($service);

            foreach ($validated as $key => $value) {
                $model->{$key} = $value;
            }
            $model->save();

            return response()->json([
                'success' => true,
                'message' => 'Servicio actualizado correctamente',
                'data' => $model
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el servicio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener las asignaciones de equipo del técnico autenticado.
     *
     * @OA\Get(
     *     path="/api/v1/app-movil/equipment-assignments",
     *     summary="Listar asignaciones de equipo del usuario autenticado",
     *     tags={"Mobile App"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Asignaciones de equipo recuperadas correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="equipment_assignments",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="assigned_at", type="string", format="date-time", example="2023-10-15T14:53:00Z"),
     *                     @OA\Property(property="returned_at", type="string", format="date-time", nullable=true, example=null),
     *                     @OA\Property(property="status", type="string", example="assigned"),
     *                     @OA\Property(property="condition_on_assignment", type="string", example="good"),
     *                     @OA\Property(property="condition_on_return", type="string", nullable=true, example=null),
     *                     @OA\Property(property="notes", type="string", nullable=true, example="Equipo en buen estado"),
     *                     @OA\Property(
     *                         property="product",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=10),
     *                         @OA\Property(property="name", type="string", example="ONT GPON"),
     *                         @OA\Property(property="sku", type="string", example="ONT-XYZ-001"),
     *                         @OA\Property(property="brand", type="string", example="Huawei")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado"),
     *     @OA\Response(response=500, description="Error interno del servidor")
     * )
     */
    public function getEquipmentAssignments(): JsonResponse
    {
        try {
            $equipmentAssignments = EquipmentAssignment::where('user_id', auth()->id())
                ->with('product')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'equipment_assignments' => $equipmentAssignments
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las asignaciones de equipo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
