<?php
// When using from package (vendor):
namespace QuintenMadari\QuiltLaravelBase\Http\Controllers;

// When published, comment the line above and uncomment this:
// namespace App\Http\Controllers;

use Exception; 
use Illuminate\Routing\Controller;


class TokenController extends Controller
{
     
    public function create_token() {


        $userModel = config('auth.providers.users.model', \App\Models\User::class); //laravel user class source of truth
        $issuerName = config('quilt-base.token_issuer_user', 'FrontendTokenIssuer');
        $lifetimeMinutes = config('quilt-base.token_lifetime_minutes', 15);
        
        $user = $userModel::where('name', '=', $issuerName)->first();
        
        if (!$user) {
            return response()->json([
                'error' => "Token issuer user '{$issuerName}' not found. Please run the seeder."
            ], 404);
        }
        
        $token = $user->createToken(
            'frontend-session-token', //name
            ['session-data-request-token'], //ability
            now()->addMinutes((int)$lifetimeMinutes)
        )->plainTextToken;
        
        return response()->json([
            'bearer' => $token
        ]);
    }


    
}
