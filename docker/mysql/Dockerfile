ARG MARIADB_VERSION=10

FROM mariadb:${MARIADB_VERSION}

## Set Timezone

ARG TIMEZONE='UTC'
ENV TIMEZONE=${TIMEZONE}

RUN ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime && \
    echo $TIMEZONE > /etc/timezone

## Configure mysql user

ARG PUID='1000'
ARG PGID='1000'

RUN usermod -u ${PUID} mysql && \
    groupmod -g ${PGID} mysql

# Set mysql data owner

RUN chown -R mysql:mysql /var/lib/mysql/ /etc/mysql

# Copy waitForMySQL.sh

COPY ./scripts/waitForMySQL.sh /usr/local/bin/waitForMySQL.sh
RUN chmod +x /usr/local/bin/waitForMySQL.sh

################################################################################

USER mysql

CMD ["mysqld"]

EXPOSE 3306
