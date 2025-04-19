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
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Use an access token obtained from /api/login"
 *  )
 */
final class OpenApi
{

}
