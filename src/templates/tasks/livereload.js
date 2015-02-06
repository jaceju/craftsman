'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Start Livereload server
gulp.task('livereload', function () {
    var server = $.livereload;
    server.listen();
    $.watch([
        config.viewDir + '/**/*',
        config.publicDir + '/**/*',
        '!' + config.bowerDir + '/**/*',
    ], server.changed);
});
