<?php

return [

    'allowed_origin_patterns' => array_filter(
        array_map('trim', explode(',', env('API_CORS_AOP', '')))
    ),

];
