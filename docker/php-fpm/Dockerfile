# Base PHP block

ARG PHP_VERSION='8.2'

FROM php:${PHP_VERSION}-fpm-buster

USER root

ARG DEBIAN_FRONTEND='noninteractive'
ENV TERM='xterm'

## Set Timezone

ARG TIMEZONE='UTC'
ENV TIMEZONE=${TIMEZONE}

RUN ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime && \
    echo $TIMEZONE > /etc/timezone

## Set Locale to UTF-8

ENV LANG='en_US.UTF-8' LANGUAGE='en_US:en' LC_ALL='en_US.UTF-8' LC_CTYPE='en_US.UTF-8'

RUN apt-get update && \
    apt-get install -yqq --no-install-recommends \
        apt-utils \
        locales && \
    echo 'en_US.UTF-8 UTF-8' > /etc/locale.gen && \
    locale-gen en_US.UTF-8

## PHP Extensions & dependencies

RUN apt-get install -yqq --no-install-recommends \
        #libmemcached-dev \
        libxml2-dev libxslt-dev \
        libjpeg-dev libwebp-dev libfreetype6-dev libpng-dev \
        libzip-dev zip \
        libssl-dev \
        libpq-dev \
        libyaml-dev && \
    apt-get clean

RUN docker-php-ext-configure gd \
        --with-jpeg \
        --with-webp \
        --with-freetype && \
    docker-php-ext-install -j$(nproc) \
        gd \
        zip \
        exif \
        pdo_mysql \
        pdo_pgsql \
        opcache \
        pcntl \
        intl \
        xsl

RUN pecl channel-update pecl.php.net && \
    pecl install yaml redis xdebug && \
    docker-php-ext-enable yaml redis;

### XDebug

ARG XDEBUG_ENABLED='0'

RUN if [ "$XDEBUG_ENABLED" = "1" ]; then \
        docker-php-ext-enable xdebug; \
    fi

#############################################################################

# PHP-FPM block

## Configure www-data user

ARG PUID='1000'
ARG PGID='1000'

RUN usermod -u ${PUID} www-data && \
    groupmod -g ${PGID} www-data

## Give permissions to /var/log directory

RUN mkdir /var/log/php-fpm && \
    chown -R www-data:www-data /var/log/php-fpm

## Health check

RUN apt-get update && \
    apt-get install -yqq libfcgi0ldbl && \
    apt-get clean

ADD scripts/php-fpm-healthcheck /usr/local/bin/php-fpm-healthcheck
RUN chmod +x /usr/local/bin/php-fpm-healthcheck

## Final clean

RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

COPY ./scripts/php-fpm-run /usr/local/bin/php-fpm-run
RUN chmod +x /usr/local/bin/php-fpm-run && \
    touch /var/www/.bashrc && \
    chown www-data:www-data /var/www/.bashrc

################################################################################

USER www-data

WORKDIR /var/www

CMD ["php-fpm-run"]

EXPOSE 9000
