'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Images
gulp.task('images', function() {
    return gulp.src(config.imageSrcDir + '/**/*')
        .pipe($.plumber())
        .pipe($.cache($.imagemin({
            optimizationLevel: 3,
            progressive: true,
            interlaced: true
        })))
        .pipe(gulp.dest(config.imageBuildDir))
        .pipe($.size({ title: 'images' }));
});
