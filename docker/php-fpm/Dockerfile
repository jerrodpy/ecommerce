FROM php:8.3-fpm

ARG USER_ID=1000
ARG GROUP_ID=1000
ARG ENABLE_XDEBUG

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    jq \
    curl \
    libmemcached-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev \
    libbz2-dev\
    libxml2-dev\
    libssh2-1-dev\
    procps\
    htop\
    libzip-dev\
    unzip\
    p7zip-full\
  && rm -rf /var/lib/apt/lists/*

RUN rm /etc/apt/preferences.d/no-debian-php

RUN apt-get update -yqq && \
    apt-get --allow-releaseinfo-change update && \
    apt-get -y install libxml2-dev php-soap && \
    docker-php-ext-install soap && \
    docker-php-ext-install zip && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install bz2 && \
    docker-php-ext-install pdo_pgsql && \
    docker-php-ext-install sockets && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Enable OPcache extension
RUN docker-php-ext-enable opcache

# install redis extension
RUN printf "\n" | pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

RUN apt-get update && \
    apt-get -y install curl nano cron supervisor && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \

# Cron setup
COPY ./crontab /etc/cron.d
# RUN chmod -R 644 /etc/cron.d && crontab /etc/cron.d/laravel

# PHP config and supervisor/cron configs
COPY ./laravel.ini /usr/local/etc/php/conf.d
COPY ./xdebug.ini /usr/local/etc/php/conf.d
COPY ./xlaravel.pool.conf /usr/local/etc/php-fpm.d/
COPY ./supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./supervisor/php-fpm.conf /etc/supervisor/php-fpm.conf

# Enable or remove xdebug
RUN if [ "$ENABLE_XDEBUG" = "true" ]; then \
      pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug; \
    else \
      rm -f /usr/local/etc/php/conf.d/xdebug.ini; \
    fi


RUN groupmod -o -g ${GROUP_ID} www-data && \
    usermod -o -u ${USER_ID} -g www-data www-data

RUN mkdir -p /var/www && \
    chown -R www-data:www-data /var/www

COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /var/www

EXPOSE 9000 9001
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
