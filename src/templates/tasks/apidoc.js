'use strict';

var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var config = require('./config');

// Api Doc
gulp.task('apidoc', function(){
    $.apidoc.exec({
        src: config.appDir + '/',
        dest: config.apiDocDir
    });
});
