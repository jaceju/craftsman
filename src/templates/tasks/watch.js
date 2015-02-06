'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Watch
gulp.task('watch', ['prepare'], function() {
    gulp.start('serve');
    gulp.start('livereload');
    $.watch([config.templateSrcFiles, 'bower.json'], function () {
        gulp.start('wiredep');
    });
    $.watch(config.appFiles, function () {
        gulp.start('phpunit');
    });
    $.watch(config.scriptSrcFiles, function () {
        gulp.start('scripts');
    });
    $.watch(config.styleSrcFiles, function () {
        gulp.start('styles');
    });
    $.watch(config.imageSrcDir + '/**/*', function () {
        gulp.start('images');
    });
});
