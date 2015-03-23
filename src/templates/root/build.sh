#!/bin/bash
BUNDLE=$(which bundle)
NPM=$(which npm)
BOWER="./node_modules/bower/bin/bower"
GULP="./node_modules/gulp/bin/gulp.js"
COMPOSER=$(which composer)
PHP=$(which php)

if [ -n "$BUNDLE" ]; then
    ${BUNDLE} install --path=.bundle
    export BUNDLE=${BUNDLE}
fi

if [ -n "$NPM" ]; then
    ${NPM} prune && ${NPM} install
    ${BOWER} prune && ${BOWER} install
    UG=`echo "$USE_GULP" | awk '{ print tolower($1) }'`
    if [ "${UG}" = "yes" ]; then
        ${GULP}
    fi
fi

if [ -n "$COMPOSER" ]; then
    ${COMPOSER} require "codeception/codeception:*" --dev --no-update
    ${COMPOSER} install
    ${PHP} artisan key:generate
fi
