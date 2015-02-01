'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();

// PHP unit testing
gulp.task('phpunit', function() {
    return gulp.src('phpunit.xml')
        .pipe($.phpunit());
});
