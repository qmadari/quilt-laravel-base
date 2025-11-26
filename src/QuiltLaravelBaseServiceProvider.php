<?php

namespace QuintenMadari\QuiltLaravelBase;

use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

use QuintenMadari\QuiltLaravelBase\Commands\EntrypointSetup;
use QuintenMadari\QuiltLaravelBase\Http\Middleware\DocsValidateApiSecret;
use QuintenMadari\QuiltLaravelBase\Http\Middleware\ValidateApiSecret;
use QuintenMadari\QuiltLaravelBase\Http\Controllers\DocsTokenController;
use QuintenMadari\QuiltLaravelBase\Http\Controllers\TokenController;

class QuiltLaravelBaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
	    AboutCommand::add('Quilt Laravel Base', function () {
            $composerPath = __DIR__ . '/../composer.json';
            $composer = json_decode(file_get_contents($composerPath), true);
            $repo = $composer['homepage'] ?? 'N/A';
            $description = $composer['description'] ?? 'N/A';
            
            return [
                'Version' => InstalledVersions::getVersion('qmadari/quilt-laravel-base') ?? 'dev',
                'Description' => $description,
                'Repository' => $repo,
                'Config Published' => file_exists(config_path('quilt-base.php')) ? '<fg=green>Published</>'  : '<fg=yellow>Not Published</>',
                'View Published' => file_exists(resource_path('views/api-landing.blade.php')) ? '<fg=green>Published</>'  : '<fg=yellow>Not Published</>',
                'CSS Published' => file_exists(public_path('css/api-landing.css')) ? '<fg=green>Published</>'  : '<fg=yellow>Not Published</>',
        
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

        // Publish CSS + Images + .well-known
        $this->publishes([
            __DIR__.'/public/css/api-landing.css' => public_path('css/api-landing.css'),
            __DIR__.'/public/vu-logo-dark.png' => public_path('vu-logo-dark.png'),
            __DIR__.'/public/vu-logo.png' => public_path('vu-logo.png'),
            __DIR__.'/public/.well-known/security.txt' => public_path('.well-known/security.txt'),
        ], 'public');


        // // Publish Middleware
        // $this->publishes([
        //     __DIR__.'/Http/Middleware/DocsValidateApiSecret.php' => app_path('Http/Middleware/DocsValidateApiSecret.php'),
        // ], 'middleware');
        // $this->publishes([
        //     __DIR__.'/Http/Middleware/ValidateApiSecret.php' => app_path('Http/Middleware/ValidateApiSecret.php'),
        // ], 'middleware');

        // // Publish Controllers
        // $this->publishes([
        //     __DIR__.'/Http/Controllers/DocsTokenController.php' => app_path('Http/Middleware/DocsTokenController.php'),
        // ], 'controllers');
        // $this->publishes([
        //     __DIR__.'/Http/Controllers/TokenController.php' => app_path('Http/Middleware/TokenController.php'),
        // ], 'controllers');

        // // Publish seeder. // If this is published, the namespace in the published file, and usage in entrypoint should also be updated.
        // $this->publishes([
        //     __DIR__.'/Seeders/FrontendTokenIssuerSeeder.php' => database_path('seeders/FrontendTokenIssuerSeeder.php'),
        // ], 'seeders');
        
	    // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                EntrypointSetup::class,
            ]);
        }

        $router = $this->app->make(Router::class); // this doesn't make a new router since it's a singleton, 
                                                   // it resolves the original/current router from laravel's service container
        $router->aliasMiddleware('docs.api.secret.validation', DocsValidateApiSecret::class); // so we can update it's attributes directly (registry of shared objects). 
                                                                                              // same as updating bootstrap/app.php manually
        $router->aliasMiddleware('api.secret.validation', ValidateApiSecret::class);

        $router->aliasMiddleware('abilities', \Laravel\Sanctum\Http\Middleware\CheckAbilities::class;

        $router->aliasMiddleware('ability', \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class);


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
        // replaceConfigRecursivelyFrom uses array_replace_recursive the wrong way around for my use case. 
        // Updating some keys manually instead.

        // manually updating allowed_origin_patterns key in cors: 
        $this->app['config']->set(
            'cors.allowed_origins_patterns',
            array_filter( array_map('trim', explode(',', env('API_CORS_AOP', ''))))
            // array_filter( array_map('trim', explode(',', env('API_CORS_AOP', 'https://sisyphus.labs.vu.nl, https://bigfoot.psy.vu.nl'))))
        );
            
        // manually updating channels.daily.path key in logging (verify with php artisan config:show logging): 
        $this->app['config']->set(
            'logging.channels.daily.path',
            storage_path('logs/laravel-'.posix_getpwuid(posix_geteuid())['name'].'.log')
        );

        // add mariadb skip_sll key (verify with php artisan config:show database)
        $this->mergeConfigFrom(
            __DIR__.'/config/database.php', 'database.connections.mariadb'
        );

    }

    protected function registerRoutes()
    {
        // register route if not disabled in config; needs to be run after boot due to config dependency
        // (verify with php artisan route:list)
        if (!config('quilt-base.skip_api_landing_route_registration', false)) {
            Route::group(['middleware' => 'web'], function () {
                Route::get('/', function () {
                    return view('api-landing');
                });
            });
        }

        // Token + DocsToken endpoint route
        if (!config('quilt-base.skip_token_route_registration', false)) {

            Route::prefix('api')
                ->middleware(['api', 'api.secret.validation']) 
                ->post('/token', [TokenController::class, 'create_token']);
            
            Route::prefix('api')
                ->middleware(['api', 'docs.api.secret.validation']) 
                ->post('/docs/token', [DocsTokenController::class, 'create_docs_token']);
    
        }
        
        
    }

}
