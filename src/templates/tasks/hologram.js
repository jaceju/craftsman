'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Style Guide / UI Patterns
gulp.task('hologram', function() {
    return gulp.src('hologram.yml')
        .pipe($.hologram({
            bundler: config.useBundle
        }));
});
