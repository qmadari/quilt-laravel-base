<?php

return [
    // skip registering the web root route (if true, points to the default laravel page instead of api landing page)
    'skip_api_landing_route_registration' => false,

    'entrypoint_steps' => [
        'scribe-generate' => true,
        'seed-apidocstokenissuer' => true,
        'seed-frontendtokenissuer' => true,
    ],

    // Custom commands with or without arguments
    'custom_entrypoint_commands' => [
        // command only format e.g.
	    //'cache:clear',
        // commands with arguments e.g.
        // 'db:seed' => [
        //     '--class' => 'UserIdSeeder',
        //     '--force' => true,
        // ],
        // 'custom:command' => [
        //     '--option' => 'value',
        // ],
    ],
];
