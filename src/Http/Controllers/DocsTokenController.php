<?php

// When using from package (vendor):
namespace QuintenMadari\QuiltLaravelBase\Http\Controllers;

// When published, comment the line above and uncomment this:
// namespace App\Http\Controllers;


use Exception; 
use Illuminate\Routing\Controller;

class DocsTokenController extends Controller
{
    /**
     * Create Docs Token
     * 
     * Generate a temporary bearer token for docs session data requests.
     * This token is valid for 15 minutes and has limited permissions.
     * 
     * @bodyParam api_secret string required The API secret for authentication. Example: 12345
     * 
     * @response 200 {
     *   "bearer": "1|abcdef123456..."
     * }
     * @response 404 {
     *   "error": "404. Token issuer user 'FrontendTokenIssuer' not found. Please run the seeder."
     * }
     * @response 500 {
     *   "error": "500 Server error. Possible cause: User model must use HasApiTokens trait",
     *   "instructions": "Add \"use Laravel\\Sanctum\\HasApiTokens;\" to your User model"
     * }
     * 
     * @group Authentication
     */
    public function create_docs_token() {
       
        $userModel = config('auth.providers.users.model', \App\Models\User::class); //laravel user class source of truth
        $issuerName = config('quilt-base.token_issuer_user', 'ApiDocsTokenIssuer');
        $lifetimeMinutes = config('quilt-base.token_lifetime_minutes', 15);
        
        $user = $userModel::where('name', '=', $issuerName)->first();
        
        if (!$user) {
            return response()->json([
                'error' => "Token issuer user '{$issuerName}' not found. Please run the seeder."
            ], 404);
        }
        
        $token = $user->createToken(
            'docs-session-token', //name
            ['docs-data-request-token'], //ability
            now()->addMinutes((int)$lifetimeMinutes)
        )->plainTextToken;
        
        return response()->json([
            'bearer' => $token
        ]);
    }

}
