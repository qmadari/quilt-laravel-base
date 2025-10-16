# Quilt Laravel Base API Setup

Laravel API Landingpage

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


## License

This project is licensed under the MIT License - see the LICENSE file for details.

**Note:** This package is intended for internal use within TO3 VU Amsterdam research projects. The code is under MIT license, however no guarantees of support are given to external users. Please consult with your department before using or distributing externally. Feel free to reach out to me for any further questions.



