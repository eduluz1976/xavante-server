FROM alpine:3.20
# Set the maintainer label:
LABEL maintainer="Eduardo Luz <eduardo.luz@eduardo-luz.com>"

# Set working directory:
WORKDIR /var/www/html

RUN apk update \
    && apk upgrade \
    && apk add apache2 oniguruma-dev curl supervisor gcc make  \
       g++ zlib-dev git autoconf automake gawk build-base \
       php82-cli php82-apache2 php82-dev php82-phar php82-xml \
       php82-iconv php82-soap php82-simplexml php82-xmlwriter \
       php82-tokenizer php82-dom php82-fileinfo php82-mbstring \
       php82-mysqli php82-pdo_mysql php82-json php82-pdo php82-curl \
       php82-ctype php82-xdebug php82-pecl-mongodb php82-pecl-redis \
       php82-pecl-xdebug \     
    && rm -f /var/cache/apk/*

RUN sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/g' /etc/php82/php.ini
RUN ln -s /usr/bin/php82 /usr/bin/php

# Create apache user home directory:
RUN mkdir -p /var/www/html

# Copy application files:
ADD app /var/www/html
ADD .docker/app/run.sh /tmp
RUN chmod 755 /tmp/run.sh

# Install Composer:
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer 
COPY .env /var/www/html
RUN composer install --no-scripts

# Copy apache config:
COPY .docker/app/config/httpd.conf /etc/apache2/httpd.conf


# Allow apache user to own certain directories:
RUN chown -R apache:apache /var/www/html
RUN chown -R apache:apache /run/apache2
RUN chown apache:apache /tmp/run.sh


# Run as user as apache:
USER apache

# Expose container port:
EXPOSE 8080

# Start httpd service:
CMD ["sh","/tmp/run.sh"]