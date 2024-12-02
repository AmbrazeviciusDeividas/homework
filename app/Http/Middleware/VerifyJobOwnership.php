<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class VerifyJobOwnership
{
    public function handle($request, Closure $next)
    {
        $jobId = $request->route('id');

        $job = Redis::get("job:$jobId");
        if (!$job) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return $next($request);
    }
}
