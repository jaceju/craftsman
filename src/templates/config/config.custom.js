'use strict';

var fs = require('fs');

var config = new function () {
    // Environment
    this.useBundle = fs.existsSync('.bundle');

    // Back-end
    this.appDir = 'app';
    this.appFiles = 'app/**/*.php';
    this.templateDir = 'resources/templates';
    this.templateExt = '.php';
    this.viewDir = 'resources/views';

    // Front-end
    this.assetsDir = 'resources/assets';
    this.bowerDir = 'public/bower_components';
    this.publicDir = 'public';
    this.styleExt = '.scss';
    this.styleCacheDir = '.sass-cache';

    // Document
    this.styleGuideDir = 'docs/styleguide';
    this.apiDocDir = 'docs/api';

    // Compiled
    this.scriptDestDir = 'public/scripts';
    this.styleDestDir = 'public/styles';

    // Built
    this.scriptBuildDir = 'public/js';
    this.styleBuildDir = 'public/css';
    this.imageBuildDir = 'public/images';
    this.fontBuildDir = 'public/fonts';

    // Source
    this.scriptSrcDir = this.assetsDir + '/scripts';
    this.styleSrcDir = this.assetsDir + '/styles';
    this.fontSrcDir = this.assetsDir + '/fonts';
    this.imageSrcDir = this.assetsDir + '/images';
    this.scriptSrcFiles = this.scriptSrcDir + '/**/*.js';
    this.styleSrcMainFiles = this.styleSrcDir + '/*' + this.styleExt;
    this.styleSrcFiles = this.styleSrcDir + '/**/*' + this.styleExt;
    this.templateSrcFiles = this.templateDir + '/**/*' + this.templateExt;
};

module.exports = config;
