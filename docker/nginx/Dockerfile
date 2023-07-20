FROM nginx:stable

RUN apt-get update && \
    apt-get install -yqq --no-install-recommends \
        bash \
        openssl \
        libssl-dev \
        nano \
        htop \
        ca-certificates \
        wget \
        socat && \
    apt-get clean

## Configure www-data user

ARG PUID='1000'
ARG PGID='1000'

RUN usermod -u ${PUID} www-data && \
    groupmod -g ${PGID} www-data && \
    chsh -s /bin/bash www-data

RUN mkdir /var/run/nginx /var/www/ && \
    chown -R www-data:www-data /var/cache/nginx /var/run/nginx /var/www

COPY ./scripts/nginx-run /usr/local/bin/nginx-run
RUN chmod +x /usr/local/bin/nginx-run

################################################################################

WORKDIR /var/www

CMD [ "nginx-run" ]
