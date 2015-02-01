'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Watch
gulp.task('watch', ['prepare'], function() {
    gulp.start('serve');
    gulp.start('livereload');
    gulp.watch([config.templateSrcFiles, 'bower.json'], ['wiredep']);
    gulp.watch(config.appFiles, ['phpunit']);
    gulp.watch(config.scriptSrcFiles, ['scripts']);
    gulp.watch(config.styleSrcFiles, ['styles']);
    gulp.watch(config.imageSrcDir + '/**/*', ['images']);
});
