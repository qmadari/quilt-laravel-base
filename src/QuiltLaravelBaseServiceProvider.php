<?php

namespace QuintenMadari\QuiltLaravelBase;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use QuintenMadari\QuiltLaravelBase\Commands\EntrypointHelper;

class QuiltLaravelBaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
	    // Publish EntrypointHelper command (for customizability. If not published, only available in vendor)
        //  $this->publishes([
        //      __DIR__.'/Commands/EntrypointHelper.php' => app_path('Console/Commands/EntrypointHelper.php'),
        //  ], 'commands');

	    // Config to customize entrypoint setup commands
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

        // Register routes
        $this->registerRoutes();

	    // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                EntrypointSetup::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/app.php', 'app'
        );

        $this->mergeConfigFrom(
            __DIR__.'/config/cors.php', 'cors'
        );

        $this->mergeConfigFrom(
            __DIR__.'/config/logging.php', 'logging'
        );

        $this->mergeConfigFrom(
            __DIR__.'/config/database.php', 'database'
        );

    	$this->mergeConfigFrom(
            __DIR__.'/config/quilt-base.php', 'quilt-base'
    	);
    }

    protected function registerRoutes()
    {
        // register route if not disabled in config
        if (!config('quilt-base.skip_api_landing_route_registration', false)) {
            Route::group(['middleware' => 'web'], function () {
                Route::get('/', function () {
                    return view('api-landing');
                });
            });
        }
    }
}
