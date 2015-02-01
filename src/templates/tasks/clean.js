'use strict';

var gulp = require('gulp');
var del = require('del');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Clean
gulp.task('clean:develop', function(cb) {
    del([
        config.viewDir,
        config.scriptDestDir,
        config.styleDestDir,
        config.styleCacheDir
    ], cb);
});

// Clean Cache
gulp.task('clean:cache', function (cb) {
    return $.cache.clearAll(cb);
});

// Clean
gulp.task('clean', ['clean:develop', 'clean:cache'], function(cb) {
    del([
        config.scriptBuildDir,
        config.styleBuildDir,
        config.imageBuildDir,
        config.fontBuildDir,
        config.styleGuideDir,
        config.apiDocDir
    ], cb);
});

// Clean temporary assets
gulp.task('clean:temporary', function (cb) {
    del([
        config.viewDir + '/js',
        config.viewDir + '/css',
        config.scriptDestDir,
        config.styleDestDir,
        config.styleCacheDir
    ], cb);
});
