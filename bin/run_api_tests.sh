#!/bin/bash

docker compose exec app vendor/bin/phpunit --do-not-cache-result --testdox tests/api 

if [ $? -ne 0 ]; then
    echo "API tests failed."
    exit 1
else
    echo "API tests passed successfully."
fi