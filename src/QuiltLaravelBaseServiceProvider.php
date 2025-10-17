<?php

namespace QuintenMadari\QuiltLaravelBase;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use QuintenMadari\QuiltLaravelBase\Commands\EntrypointSetup;

class QuiltLaravelBaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
	    AboutCommand::add('Quilt Laravel Base', function () {
            $composerPath = __DIR__ . '/../composer.json';
            $composer = json_decode(file_get_contents($composerPath), true);
            
            return [
                'Version' => InstalledVersions::getVersion('qmadari/quilt-laravel-base') ?? 'dev',
                'Description' => $composer['description'] ?? 'N/A',
                'Repository' => '<href='.$composer['homepage'] ?? 'N/A'.'>GitHub</>',
                'Config Published' => file_exists(config_path('quilt-base.php')) ? '✓' : '✗',
                'View Published' => file_exists(resource_path('views/api-landing.blade.php')) ? '✓' : '✗',
                'CSS Published' => file_exists(public_path('css/api-landing.css')) ? '✓' : '✗',
        
            ];
        });

	    // Publish quilt-base config to customize 
        // - entrypoint setup command, 
        // - api landing page web route
        // - descriptive config vars
	    $this->publishes([
	        __DIR__.'/config/quilt-base.php' => config_path('quilt-base.php'),
	    ], 'config');

        // Publish views
        $this->publishes([
            __DIR__.'/resources/views/api-landing.blade.php' => resource_path('views/api-landing.blade.php'),
        ], 'views');

        // Publish CSS
        $this->publishes([
            __DIR__.'/public/css/api-landing.css' => public_path('css/api-landing.css'),
        ], 'public');

	    // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                EntrypointSetup::class,
            ]);
        }

        // Register routes after boot
        $this->booted(function () {
            $this->registerRoutes();
        });

        // Disabled publishing EntrypointSetup command (for customizability. If not published, only available in vendor)
        // Instead built config in a way to add custom project specific commands, and toggle EntrypointSetup functions.
        //  $this->publishes([
        //      __DIR__.'/Commands/EntrypointSetup.php' => app_path('Console/Commands/EntrypointSetup.php'),
        //  ], 'commands');

        
    }

    public function register()
    {
        # https://laracasts.com/discuss/channels/general-discussion/how-does-mergeconfigfrom-work
        // replaceConfigRecursivelyFrom uses array_replace_recursive the wrong way around for mmy use case. 
        // Updating some keys manually instead.

        // manually updating allowed_origin_patterns key in cors: 
        $this->app['config']->set(
            'cors.allowed_origins_patterns',
            array_filter( array_map('trim', explode(',', env('API_CORS_AOP', ''))))
            // array_filter( array_map('trim', explode(',', env('API_CORS_AOP', 'https://sisyphus.labs.vu.nl, https://bigfoot.psy.vu.nl'))))
        );
            
        // manually updating channels.daily.path key in logging: 
        $this->app['config']->set(
            'logging.channels.daily.path',
            storage_path('logs/laravel-'.posix_getpwuid(posix_geteuid())['name'].'.log')
        );

        // add skip_sll key
        $this->mergeConfigFrom(
            __DIR__.'/config/database.php', 'database.connections.mariadb'
        );

        // $this->mergeConfigFrom(
        //     __DIR__.'/config/app.php', 'app'
        // );

    	// $this->mergeConfigFrom(
        //     __DIR__.'/config/quilt-base.php', 'quilt-base'
    	// );
    }

    protected function registerRoutes()
    {
        // register route if not disabled in config; needs to be run after boot due to config dependency
        if (!config('quilt-base.skip_api_landing_route_registration', false)) {
            Route::group(['middleware' => 'web'], function () {
                Route::get('/', function () {
                    return view('api-landing');
                });
            });
        }
    }

}
