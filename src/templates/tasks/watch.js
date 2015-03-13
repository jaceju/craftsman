'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Watch
gulp.task('watch', ['prepare'], function() {
    gulp.start('serve');

    $.watch([config.templateSrcFiles, 'bower.json'], function () {
        gulp.start('wiredep');
    });
    $.watch([config.appFiles, config.appTestFiles], function () {
        gulp.start('phpunit');
    });
    $.watch(config.scriptSrcFiles, function () {
        gulp.start('jshint');
    });
    $.watch(config.styleSrcFiles, function () {
        gulp.start('sass');
    });
    $.watch(config.imageSrcDir + '/**/*', function () {
        gulp.start('images');
    });
});
