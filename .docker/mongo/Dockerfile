FROM mongo:4

ENV MONGO_INITDB_DATABASE=${MONGO_DATABASE}

COPY .docker/mongo/init.js /docker-entrypoint-initdb.d

