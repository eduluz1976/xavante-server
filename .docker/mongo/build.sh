#!/bin/sh


sed  -i "s/{MONGO_INITDB_ROOT_USERNAME}/${MONGO_INITDB_ROOT_USERNAME}/g" /docker-entrypoint-initdb.d/01-create-system-user.js 
sed  -i "s/{MONGO_INITDB_ROOT_PASSWORD}/${MONGO_INITDB_ROOT_PASSWORD}/g" /docker-entrypoint-initdb.d/01-create-system-user.js 
sed  -i "s/{MONGO_INITDB_DATABASE}/${MONGO_INITDB_DATABASE}/g" /docker-entrypoint-initdb.d/01-create-system-user.js 
