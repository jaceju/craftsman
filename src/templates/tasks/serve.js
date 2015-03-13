'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var browserSync = require('browser-sync');
var config = require('./config');

// Start Web server
gulp.task('serve', function () {

    if (config.proxy) {
        browserSync({
            proxy: config.proxy
        });
    } else {
        $.connectPhp.server({
            base: 'public',
            port: config.port,
            router: '../server.php'
        }, function () {
            browserSync({
                proxy: 'localhost:' + config.port
            });
        });
    }

    $.watch([
        config.viewDir + '/**/*',
        config.publicDir + '/**/*',
        '!' + config.bowerDir + '/**/*',
    ], browserSync.reload);
});
