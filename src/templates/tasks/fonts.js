'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Fonts
gulp.task('fonts', function () {
    return gulp.src([
            config.fontSrcDir + '/*.{otf,eot,svg,ttf,woff}',
            config.bowerDir + '/**/fonts/**/*.{otf,eot,svg,ttf,woff}'
        ])
        .pipe($.flatten())
        .pipe(gulp.dest(config.fontBuildDir))
        .pipe($.size({ title: 'fonts' }));
});
