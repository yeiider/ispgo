<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use OpenApi\Annotations as OA;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 */
class AuthController extends Controller
{

    /**
     * Log in a user.
     *
     * @OA\Post(
     *     path="/api/login",
     *     summary="Log in a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", description="User's email or username", example="user@example.com"),
     *             @OA\Property(property="password", type="string", description="User's password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login with access token",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="access_token", type="string", description="OAuth access token"),
     *             @OA\Property(property="token_type", type="string", description="Type of token", example="Bearer"),
     *             @OA\Property(property="expires_in", type="integer", description="Token expiration time in seconds", example=3600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message", example="Invalid credentials")
     *         )
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $response = Http::asForm()->post(url('/oauth/token'), [
            'grant_type' => 'password',
            'client_id' => $request->client_id ?? config('services.passport.client_id'),
            'client_secret' => $request->client_secret ?? config('services.passport.client_secret'),
            'username' => $request->username,
            'password' => $request->password,
            'scope' => '*',
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
