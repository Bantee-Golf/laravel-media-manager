# Laravel File and Media Handling Package

This package adds the following.

-
-
-

### Version Compatibility

| Laravel Version | This Package Version               | Branch         |
| ---------------:| ----------------------------------:|---------------:|
| v8              | 3.x                                | master         |  
| v6/v7           | See [CHANGELOG.md](CHANGELOG.md)   |                |

## Installation

Add the private repository in your 'composer.json` file.
```
"repositories": [
    {
        "type":"vcs",
        "url":"git@bitbucket.org:elegantmedia/laravel-media-manager.git"
    }
]
```

Add the repository to the required list on composer.json
`composer require emedia/laravel-media-manager`

## Publish Configuration files

```
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravelRecent"
```

## Usage


## Common Issues



## Contributing

- Found a bug? Report as an issue and if you can, submit a pull request.
- Please see [CONTRIBUTING](CONTRIBUTING.md) and for details.

Copyright (c) 2020 Elegant Media.
