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

# PHP-CLI block

## Configure php-cli user

ARG PUID='1000'
ARG PGID='1000'

RUN groupadd -g ${PGID} php-cli && \
    useradd -u ${PUID} -g php-cli -m php-cli -G php-cli && \
    usermod -p "*" php-cli -s /bin/bash

## Install CLI-tools

RUN apt-get update && \
    apt-get install -yqq --allow-downgrades --allow-remove-essential --allow-change-held-packages \
        git \
        mc \
        vim nano \
        sqlite3 \
        mariadb-client \
        # postgresql-client \
        # python3 python3-pip python3-dev \
        curl && \
    apt-get clean

# RUN python3 -m pip install --upgrade pip && \
    # python3 -m pip install --upgrade virtualenv;

RUN curl -sS -L https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

COPY ./configs/composer.json /home/php-cli/.composer/composer.json
RUN chown -R php-cli:php-cli /home/php-cli/.composer

USER php-cli

RUN composer global install && \
    echo "" >> ~/.bashrc && \
    echo 'export PATH="/var/www/api/bin:/var/www/api/vendor/bin:~/.composer/vendor/bin:$PATH"' >> ~/.bashrc

USER root

# RUN curl -sS -L https://get.symfony.com/cli/installer | bash && \
    # mv /root/.symfony/bin/symfony /usr/local/bin/symfony

COPY ./scripts/php-cli-run /usr/local/bin/php-cli-run
RUN chmod +x /usr/local/bin/php-cli-run

################################################################################

USER php-cli

WORKDIR /var/www

CMD [ "php-cli-run" ]
