#!/bin/bash


docker compose exec app vendor/bin/phpunit tests/unit

if [ $? -ne 0 ]; then
    echo "Unit tests failed."
    exit 1
else
    echo "Unit tests passed successfully."
fi