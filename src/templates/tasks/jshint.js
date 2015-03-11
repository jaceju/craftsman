'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Scripts
gulp.task('jshint', function() {
    return gulp.src(config.scriptSrcFiles)
        .pipe($.jshint('.jshintrc'))
        .pipe($.jshint.reporter('jshint-stylish'))
        .pipe(gulp.dest(config.scriptDestDir))
        .pipe($.size({ title: 'jshint' }));
});
