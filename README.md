# Craftsman

Enhance Laravel 5 Elixir tasks.

## Integration

* [Gulp](http://gulpjs.com/)
* [Bower](http://bower.io/)
## Requirement

* Linux or OS X
* PHP 5.4+
* Node 0.10.0+
* npm 2.0.0+
* Composer 1.0+

## Installation

Add `~/.composer/vendor/bin/` to `PATH` environment variable first. Then can install the package by:

```bash
composer global require jaceju/craftsman
```

## Usage

Create a Laravel project first:

```bash
laravel new project-name
```

Then initialize the project:

```bash
craftsman init project-name
```

or:

```bash
cd project-name
craftsman init
```

then you can run `gulp` for development or `gulp --production` for build.

## Powered by

[jaceju/clitool-boilerplate](https://github.com/jaceju/clitool-boilerplate)

## License

MIT
