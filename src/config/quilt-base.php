<?php

return [
    // some descriptives
    'repo_url' => env('APP_REPO_URL','https://bitbucket.org'),
    'api_version' => env('API_VERSION','0.0.0'),
    'api_description' => env('API_DESCRIPTION','API Landing Page'),

    // skip registering the web root route (if true, points to the default laravel page instead of api landing page)
    'skip_api_landing_route_registration' => false,
    'skip_token_route_registration' => false,
    
    // Docs API secret validation
    'protect_docs' => env('QUILT_PROTECT_DOCS', true),
    'docs_api_secret' => env('API_SECRET_DOCS', '12345'),
    
    // Token issuer configuration
    'token_issuer_user' => env('TOKEN_ISSUER_USER', 'FrontendTokenIssuer'),
    'token_issuer_user_docs' => env('TOKEN_ISSUER_USER_DOCS', 'ApiDocsTokenIssuer'),
    'token_lifetime_minutes' => env('TOKEN_LIFETIME_MINUTES', 15),
    
    // Enable parts of container entrypoint helper command
    'entrypoint_steps' => [
        'scribe-generate' => true,
        'seed-apidocstokenissuer' => true,
        'seed-frontendtokenissuer' => true,
    ],

    
    // Add more custom commands to the entrypoint helper if necessary (ones created in this specific project)
    // with or without arguments
    'custom_entrypoint_commands' => [
        //// Examples:
        
        //// command only format e.g.
	    //'cache:clear',
        
        //// commands with arguments e.g.
        // 'db:seed' => [
        //     '--class' => 'UserIdSeeder',
        //     '--force' => true,
        // ],
        // 'custom:command' => [
        //     '--option' => 'value',
        // ],
    ],
];
