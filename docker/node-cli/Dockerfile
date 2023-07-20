ARG NODE_VERSION='19'

FROM node:${NODE_VERSION}-buster-slim

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

## Configure node user

ARG PUID='1000'
ARG PGID='1000'

RUN usermod -u ${PUID} node && \
    groupmod -g ${PGID} node

## Install CLI-tools

RUN apt-get install -yqq --allow-downgrades --allow-remove-essential --allow-change-held-packages \
        yarn \
        nano \
        curl && \
    apt-get clean

USER node

RUN mkdir -p /home/node/.npm-global/bin
RUN npm config set prefix "/home/node/.npm-global"
ENV PATH="/home/node/.npm-global/bin:${PATH}"

USER root

COPY ./scripts/node-cli-run /usr/local/bin/node-cli-run
RUN chmod +x /usr/local/bin/node-cli-run

################################################################################

USER node

WORKDIR /var/www

CMD [ "node-cli-run" ]
