<?php

// When using from package (vendor):
namespace QuintenMadari\QuiltLaravelBase\Http\Middleware;

// When published, comment the line above and uncomment this:
//namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocsValidateApiSecret
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validated = $request->validate([
            'api_secret' => 'required|string',
        ]);
        $requestSecret = $validated['api_secret'];
        $secret = '12345';

        // Compare hashes
        if ($secret !== $requestSecret) {
            $resp['error'] = 'Unauthorized API access';
            $resp['received'] = $requestSecret;
            return response()->json($resp,401);
        }

        return $next($request);
    }
}
