FROM mongo:8

COPY .docker/mongo/build.sh /usr/local/bin/build.sh
COPY .docker/mongo/init/* /docker-entrypoint-initdb.d/


RUN chmod +x /usr/local/bin/build.sh && /usr/local/bin/build.sh

# RUN sed  "s/{MONGO_USERNAME}/${MONGO_USERNAME}/g" /docker-entrypoint-initdb.d/01-create-user.js > /docker-entrypoint-initdb.d/01-create-user.js 
# RUN sed  "s/{MONGO_PASSWORD}/${MONGO_PASSWORD}/g" /docker-entrypoint-initdb.d/01-create-user.js > /docker-entrypoint-initdb.d/01-create-user.js 
# RUN sed  "s/{MONGO_INITDB_DATABASE}/${MONGO_INITDB_DATABASE}/g" /docker-entrypoint-initdb.d/01-create-user.js > /docker-entrypoint-initdb.d/01-create-user.js 
