#!/bin/sh

SEARCH_PATHS='./app/ ./bootstrap/*.php ./config/ ./database/ ./routes/ ./tests/'

./vendor/bin/phpcs --cache --standard=resources/ci/.php-csniffer.xml $SEARCH_PATHS &&

exit $?
