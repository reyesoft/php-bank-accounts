#!/bin/sh

./vendor/bin/phpmnd  --hint --include-numeric-string --non-zero-exit-on-violation ./app/
# ./vendor/bin/phpmnd  --hint --include-numeric-string --non-zero-exit-on-violation -q ./database/

exit $?
