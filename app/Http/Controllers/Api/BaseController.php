<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Success response method.
     *
     * @param  array  $result
     * @param  string  $message
     * @return JsonResponse
     */
    public function sendResponse($result, $message = '')
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * Error response method.
     *
     * @param  string  $error
     * @param  array  $errorMessages
     * @param  int  $code
     * @return JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Validation error response method.
     *
     * @param  array  $errors
     * @return JsonResponse
     */
    public function sendValidationError($errors)
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation Error',
            'data' => $errors
        ], 422);
    }
} 