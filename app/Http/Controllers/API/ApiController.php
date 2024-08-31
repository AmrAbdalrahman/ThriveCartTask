<?php

namespace App\Http\Controllers\API;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    /**
     * @param  $payload
     * @param int $statusCode
     * @param string|null $message
     * @return JsonResponse
     */
    protected function response($payload, int $statusCode, string $message = null): JsonResponse
    {
        if (is_null($message)) {
            $message = match ($statusCode) {
                HttpStatusCodeEnum::CREATED->value => 'Resource Added Successfully',
                HttpStatusCodeEnum::INTERNAL_SERVER_ERROR->value => 'Something Went Wrong.',
                HttpStatusCodeEnum::UNAUTHORIZED->value => 'Unauthorised',
                default => null,
            };
        }
        $request = Request()->segment(1);
        // has version
        $version = str_contains($request, 'v') ? substr($request, 1) : 1;

        $response = [
            'version' => $version,
            'data' => $payload,
            'code' => $statusCode
        ];
        if ($message) {
            $response['message'] = $message;
        }
        return response()->json($response, $statusCode, [], JSON_INVALID_UTF8_IGNORE);
    }

}
