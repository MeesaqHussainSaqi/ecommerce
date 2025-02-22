
<?php

namespace App\Response;

class ApiResponse {
    public static function success($message, $data = null, $code = 200) {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function error($message, $code = 400) {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], $code);
    }

    public static function collection($data, $message = 'Data retrieved successfully') {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'meta' => [
                'total' => count($data)
            ]
        ]);
    }

    public static function single($data, $message = 'Data retrieved successfully') {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }
}
