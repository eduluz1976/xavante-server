#!/bin/bash


docker compose down

rm -rf .var/db_data

docker compose build --no-cache
