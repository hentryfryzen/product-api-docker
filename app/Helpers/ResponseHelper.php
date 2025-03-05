<?php

namespace App\Helpers;

class ResponseHelper
{
    /**
     * Format a success response with statusCode, message, and data.
     *
     * @param string $message The success message
     * @param mixed $data The data to return (optional)
     * @param int $statusCode The HTTP status code (optional, default is 200)
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($message, $data = null, $statusCode = 200)
    {
        $response = [
            'statusCode' => $statusCode,
            'message' => $message,
        ];

        // Include data only if it's provided
        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Format an error response with statusCode and message.
     *
     * @param string $message The error message
     * @param int $statusCode The HTTP status code (optional, default is 500)
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message, $statusCode = 500)
    {
        return response()->json([
            'statusCode' => $statusCode,
            'message' => $message
        ], $statusCode);
    }
}
