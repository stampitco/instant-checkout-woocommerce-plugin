#!/usr/bin/env bash

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[0;93m'
NC='\033[0m'


if [ -z ${STAMP_API_URL+x} ]; then
    echo -e ${RED}"STAMP_API_URL env variable missing ${NC}";
    exit;
fi

if [ -z ${STAMP_WEB_URL+x} ]; then
    echo -e ${RED}"STAMP_WEB_URL env variable missing ${NC}";
    exit;
fi

API_URL_DEFINITION_LINE_NUMBER=$(sed -n "/define([[:blank:]]*'STAMP_API_URL'/=" constants.php)
WEB_URL_DEFINITION_LINE_NUMBER=$(sed -n "/define([[:blank:]]*'STAMP_WEB_URL'/=" constants.php)

if [ -z ${API_URL_DEFINITION_LINE_NUMBER+x} ]; then
    echo -e ${RED}"STAMP_API_URL definition not found in file: constants.php ${NC}";
    exit;
fi

if [ -z ${WEB_URL_DEFINITION_LINE_NUMBER+x} ]; then
    echo -e ${RED}"STAMP_WEB_URL definition not found in file: constants.php ${NC}";
    exit;
fi

sed -i "${API_URL_DEFINITION_LINE_NUMBER}s/.*/   define( 'STAMP_API_URL', $STAMP_API_URL );/" constants.php
sed -i "${WEB_URL_DEFINITION_LINE_NUMBER}s/.*/   define( 'STAMP_WEB_URL', $STAMP_WEB_URL );/" constants.php
