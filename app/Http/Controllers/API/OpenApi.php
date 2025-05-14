<?php

namespace App\Http\Controllers\API;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Documentation for Your ISO GO Application",
 *      description="This is the OpenAPI definition for your application's APIs, including endpoints for managing resources like Boxes and Users and more.",
 *      @OA\Contact(
 *          email="support@yourapp.com"
 *      )
 * )
 * @OA\Post(
 *       path="/oauth/token",
 *       operationId="authenticateUser",
 *       tags={"Authentication"},
 *       summary="Generate an access and refresh token",
 *       description="This endpoint is used to generate an access token and refresh token for the application using client credentials.",
 *       @OA\RequestBody(
 *           required=true,
 *           @OA\JsonContent(
 *               required={"client_id", "client_secret", "username", "password", "grant_type"},
 *               @OA\Property(
 *                   property="client_id",
 *                   type="string",
 *                   example="9ed315be-902f-49b2-b951-dc9fa3e9736c",
 *                   description="The client ID provided by the application"
 *               ),
 *               @OA\Property(
 *                   property="client_secret",
 *                   type="string",
 *                   example="jl2nmTwPEO0JwIMZpc9YN9PylBnMuwd5SW1x7brQ",
 *                   description="The client secret provided by the application"
 *               ),
 *               @OA\Property(
 *                   property="username",
 *                   type="string",
 *                   example="user@mail.com",
 *                   description="The username of the application user"
 *               ),
 *               @OA\Property(
 *                   property="password",
 *                   type="string",
 *                   example="123***",
 *                   description="The password of the application user"
 *               ),
 *               @OA\Property(
 *                   property="grant_type",
 *                   type="string",
 *                   example="password",
 *                   description="Grant type for the OAuth token request, usually 'password'"
 *               )
 *           )
 *       ),
 *       @OA\Response(
 *           response=200,
 *           description="Successful operation",
 *           @OA\JsonContent(
 *               @OA\Property(
 *                   property="token_type",
 *                   type="string",
 *                   example="Bearer",
 *                   description="The type of the token issued"
 *               ),
 *               @OA\Property(
 *                   property="expires_in",
 *                   type="integer",
 *                   example=1296000,
 *                   description="Time in seconds before the token expires"
 *               ),
 *               @OA\Property(
 *                   property="access_token",
 *                   type="string",
 *                   example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5ZWQzMTViZS05MDJmLTQ5YjItYjk1MS1kYzlmYTNlOTczNmMiLC...",
 *                   description="The issued access token"
 *               ),
 *               @OA\Property(
 *                   property="refresh_token",
 *                   type="string",
 *                   example="def502005e168a620160560c0b80f752470935adbe5bdb521c28a1582bc3daa971b27b9a...",
 *                   description="The issued refresh token to renew the access token"
 *               )
 *           )
 *       ),
 *       @OA\Response(
 *           response=400,
 *           description="Invalid client details or credentials"
 *       ),
 *       @OA\Response(
 *           response=401,
 *           description="Unauthorized access"
 *       )
 *  )
 */

final class OpenApi
{

}
