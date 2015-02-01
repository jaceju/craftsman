'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Styles
gulp.task('styles', function() {
    return gulp.src(config.styleSrcMainFiles)
        .pipe($.plumber())
        .pipe($.rubySass({
            container: (+new Date) + '', // bug of temporary path generating.
            bundleExec: config.useBundle,
            style: 'expanded',
            precision: 10
        }))
        .pipe($.autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest(config.styleDestDir))
        .pipe($.size({ title: 'styles' }));
});
