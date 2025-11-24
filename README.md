# Quilt Laravel Base API Setup

Somewhat ready Laravel API with a landing page.

Base API setup package with scribe documentation and landing page for TO3 Laravel projects, Faculty of Behavioural and Movement Sciences, VU Amsterdam.

## Including in a Laravel project

### 1. composer.json
To include, make sure composer.json contains:
```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/qmadari/quilt-laravel-base.git"
        }
    ],
    "require": {
        "qmadari/quilt-laravel-base": "^1.0"
    }
}
```

### 2. Require the package
Next run
`composer require qmadari/quilt-laravel-base`

### 3. Publishing assets
To publish all assets this package has to publish, run
`php artisan vendor:publish --provider="QuintenMadari\\QuiltLaravelBase\\QuiltLaravelBaseServiceProvider"`

To publish individually selected assets, pass the corresponding tags as arguments before the --provider option:
`--tag=views`
`--tag=public`
`--tag=config`
`--tag=commands`
`--tag=middleware`
`--tag=controllers`

### Non-interactive installing (e.g. docker image)
Note that when this repo is private, the `--no-interaction` flag will cause the process to error out straight away. In that case, don't use it and proceed through the authentication steps.
``` bash
composer config repositories.quilt-laravel-base vcs https://github.com/qmadari/quilt-laravel-base.git
composer require qmadari/quilt-laravel-base --no-interaction
#composer require qmadari/quilt-laravel-base:1.0.0 --no-interaction
#composer require qmadari/quilt-laravel-base:dev-main --no-interaction
#composer require qmadari/quilt-laravel-base:dev-dev --no-interaction
```

### Removing
`composer remove qmadari/quilt-laravel-base`
`composer clear-cache`


### Token Generation Endpoint

This package automatically registers a `/api/token` endpoint for generating Sanctum tokens.
No configuration is necessary, all important variables have defaults set. If customization is desired, refer to `config/quilt-base.php`


## Post require actions

### User model
The User model has to be updated with the Trait HasApiTokens to be able to interface with Sanctum to create tokens:
`use HasFactory, Notifiable, HasApiTokens;`
As first line within the User class.

### Web route
The package expects to host the API docs landing page on /. The route is registered after publishing, but web.php will still contain the default / route. You can either update this, or remove it.

### Environment file
The package registers 'cors.allowed_origins_patterns' from the cors.php config, to make the app a little more .env file configurable.
The laravel and docker env files can provide allowed urls like so:
`API_CORS_AOP="allowed.url.example.com, second.allowed.url.example.com"`


### Middleware Setup

After publishing, middleware will be immediately available to use. QuiltLaravelBaseServiceProvider sets this through `$router->aliasMiddleware`.
It can still also be manually registered in `bootstrap/app.php`, but this is no longer necessary. An example::
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'api.secret' => \App\Http\Middleware\ValidateApiSecret::class,
        'docs.api.secret' => \App\Http\Middleware\DocsValidateApiSecret::class,
        'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
        'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
    ]);
})
```

## License

MIT License - see the LICENSE file for details.

**Note:** This package is intended for internal use within TO3 VU Amsterdam research projects. While the code falls under MIT license, no guarantees of support are given to external users. Please consult with your department before using or distributing in the field. Feel free to reach out to me (q.s.r.madari@vu.nl) for any further questions.
[Oct 15th 2025]
