<?php
// For use with mergeConfigFrom in the service provider
// Service provider snippet:
//         $this->mergeConfigFrom(
//             __DIR__.'/config/database.php', 'database.connections.mariadb'
//         );
return [
    'dump' => [
            'skip_ssl' => true,
    ],
];
