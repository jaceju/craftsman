'use strict';

var gulp = require('gulp');

// Start Web server
gulp.task('serve', function () {
    var spawn = require('child_process').spawn,
        child = spawn('php', [ 'artisan', 'serve' ], { cwd: process.cwd() }),
        log = function (data) { console.log(data.toString()); },
        kill = function () { child.kill(); }
    child.stdout.on('data', log);
    child.stderr.on('data', log);
    process.on('exit', kill);
    process.on('uncaughtException', kill);
});
