#!/bin/sh

./vendor/bin/phpcpd --min-tokens=50 ./app/ ./bootstrap/*.php  ./config/ ./routes/ \
--exclude=FiscalbookExport.php,FiscalbookTrait.php,IntegrationTests \
&&

exit $?
