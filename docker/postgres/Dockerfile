ARG POSTGRES_VERSION=15

FROM postgres:${POSTGRES_VERSION}

## Set Timezone

ARG TIMEZONE='UTC'
ENV TIMEZONE=${TIMEZONE}

RUN ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime && \
    echo $TIMEZONE > /etc/timezone

## Configure postgres user

ARG PUID='1000'
ARG PGID='1000'

RUN usermod -u ${PUID} postgres && \
    groupmod -g ${PGID} postgres

# Set postgres data owner

RUN chown -R postgres:postgres /var/lib/postgresql /var/run/postgresql /etc/postgresql
COPY ./scripts/init-user-db.sh /docker-entrypoint-initdb.d/init-user-db.sh

# Copy waitForPostgreSQL.sh

COPY ./scripts/waitForPostgreSQL.sh /usr/local/bin/waitForPostgreSQL.sh
RUN chmod +x /usr/local/bin/waitForPostgreSQL.sh

################################################################################

USER postgres

CMD ["postgres", "-c", "config_file=/etc/postgresql/my.conf"]

EXPOSE 5432
