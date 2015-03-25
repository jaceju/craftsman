'use strict';

var elixir = require('laravel-elixir');
require('laravel-elixir-wiredep');
require('laravel-elixir-useref');
require('laravel-elixir-browser-sync');
require('laravel-elixir-serve');
require('laravel-elixir-sync');
require('laravel-elixir-jshint');
require('laravel-elixir-clean');
require('laravel-elixir-style-guide');
require('laravel-elixir-apidoc');

elixir(function (mix) {
    var port = 8000;

    mix.clean()
        .sass('*.scss')
        .wiredep()
        .jshint()
        .sync('resources/assets/js/**/*.js', 'public/js');

    if (elixir.config.production) {
        mix.useref({src: false})
            .version(['js/*.js', 'css/*.css'])
            .styleGuide()
            .apidoc();
    } else {
        mix.serve({
            port: port
        }).browserSync(null, {
            proxy: 'localhost:' + port,
            reloadDelay: 2000
        });
    }
});
