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

## License

MIT License - see the LICENSE file for details.

**Note:** This package is intended for internal use within TO3 VU Amsterdam research projects. While the code falls under MIT license, no guarantees of support are given to external users. Please consult with your department before using or distributing in the field. Feel free to reach out to me (q.s.r.madari@vu.nl) for any further questions.
[Oct 15th 2025]
