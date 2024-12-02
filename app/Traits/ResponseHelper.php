<?php

namespace App\Traits;

trait ResponseHelper {


    public function successResponse( $data, string $message = "Request completed successfully.", int $code = 200 ) {
        return response()->json( [
            'status'  => 'success',
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
            'meta'    => [
                'requestId' => uniqid( '', true ),
                'timestamp' => now()->toIso8601String(),
            ],
        ], $code );
    }

    public function errorResponse( string $message, array $details = null, int $code = 400 ) {
        return response()->json( [
            'status'  => 'error',
            'code'    => $code,
            'message' => $message,
            'details' => $details,
            'meta'    => [
                'requestId' => uniqid( '', true ),
                'timestamp' => now()->toIso8601String(),
            ],
        ], $code );
    }
}
