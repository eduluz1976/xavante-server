FROM mongo:8

COPY .docker/mongo/build.sh /usr/local/bin/build.sh
COPY .docker/mongo/init/* /docker-entrypoint-initdb.d/


ADD .docker/mongo/run.sh /tmp
RUN chmod 755 /tmp/run.sh


CMD ["sh","/tmp/run.sh"]