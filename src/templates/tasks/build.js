'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Build
gulp.task('build', ['wiredep', 'styles', 'scripts', 'images', 'fonts'], function() {

    var saveLicense = require('uglify-save-license');
    var assets = $.useref.assets({ searchPath: 'public' });

    return gulp.src(config.viewDir + '/**/*' + config.templateExt)
        .pipe(assets)
        .pipe($.if('*.js', $.uglify({ preserveComments: saveLicense })))
        .pipe($.if('*.css', $.csso()))
        .pipe(gulp.dest(config.styleGuideDir))
        .pipe($.rev())
        .pipe(gulp.dest('public'))
        .pipe(assets.restore())
        .pipe($.useref())
        .pipe($.revReplace({
            replaceInExtensions: [config.templateExt]
        }))
        .pipe(gulp.dest(config.viewDir));
});
