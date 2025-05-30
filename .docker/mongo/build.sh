#!/bin/sh

sed  "s/{MONGO_INITDB_ROOT_USERNAME}/${MONGO_INITDB_ROOT_USERNAME}/g" /docker-entrypoint-initdb.d/01-create-user.js > /docker-entrypoint-initdb.d/01-create-user.js 
sed  "s/{MONGO_INITDB_ROOT_PASSWORD}/${MONGO_INITDB_ROOT_PASSWORD}/g" /docker-entrypoint-initdb.d/01-create-user.js > /docker-entrypoint-initdb.d/01-create-user.js 
sed  "s/{MONGO_INITDB_DATABASE}/${MONGO_INITDB_DATABASE}/g" /docker-entrypoint-initdb.d/01-create-user.js > /docker-entrypoint-initdb.d/01-create-user.js 
