'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');
var wiredep = require('wiredep').stream;
var merge = require('merge-stream');

// Inject bower components
gulp.task('wiredep', function() {

    var styleDeps = gulp.src(config.styleSrcMainFiles)
        .pipe(wiredep({
            directory: config.bowerDir
        }))
        .pipe(gulp.dest(config.styleSrcDir));

    var tplDeps = gulp.src(config.templateSrcFiles)
        .pipe(wiredep({
            ignorePath: '../../public',
            exclude: ['modernizr']
        }))
        .pipe(gulp.dest(config.viewDir));

    return merge(styleDeps, tplDeps);
});
