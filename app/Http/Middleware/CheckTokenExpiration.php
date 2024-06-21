<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $expiration = config('sanctum.expiration') * 60; // convert to seconds
        if (Carbon::parse($accessToken->created_at)->addSeconds($expiration)->isPast()) {
            $accessToken->delete();
            return response()->json(['message' => 'Token expired.'], 401);
        }

        return $next($request);
    }
}
