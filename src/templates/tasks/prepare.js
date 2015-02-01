'use strict';

var gulp = require('gulp');
var runSequence = require('run-sequence');

// Prepare for development
gulp.task('prepare', function (cb) {
    runSequence(
        'clean:develop',
        ['wiredep', 'styles', 'scripts', 'images', 'fonts'],
    cb);
});