# Craftsman

Enhance your laravel project with bower, gulp, etc. 

Powered by [jaceju/clitool-boilerplate](https://github.com/jaceju/clitool-boilerplate)

## Integration

* [Gulp](http://gulpjs.com/)
* [Bower](http://bower.io/)
* [Hologram](http://trulia.github.io/hologram/)
* [Apidoc](http://apidocjs.com/)

## Requirement

* Linux or OS X
* PHP 5.4+
* Ruby 1.9.3+
* Gem Bundle 1.7.0+
* Node 0.10.0+
* npm 2.0.0+
* Composer 1.0+

## Installation

```bash
curl -L -O https://jaceju.github.io/craftsman/downloads/craftsman.phar
chmod +x craftsman.phar
mv craftsman.phar /usr/local/bin/craftsman
```

## Usage

Initialize project first:

```bash
craftsman init [project-folder]
```

or:

```bash
cd project-folder
craftsman init
```

then you can run `gulp` to build or `gulp watch` for development.

## Zsh auto-completion

```bash
craftsman zsh --bind craftsman > ~/.zsh/craftsman
```

Then add these lines to your `.zshrc` file:

```
source ~/.zsh/craftsman
```

## License

MIT
