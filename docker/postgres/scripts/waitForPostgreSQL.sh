#!/usr/bin/env bash

set -o errexit
set -o nounset
set -o pipefail

retry() {
  max_attempts="${1}"; shift
  retry_delay_seconds="${1}"; shift
  cmd="${@}"
  attempt_num=1

  until ${cmd}; do
    (( attempt_num >= max_attempts )) && {
      echo "Attempt ${attempt_num} failed and there are no more attempts left!"
      return 1
    }
    echo "Attempt ${attempt_num} failed! Trying again in ${retry_delay_seconds} seconds..."
    attempt_num=$[ attempt_num + 1 ]
    sleep ${retry_delay_seconds}
  done
}

retry 1>&2 ${MAX_ATTEMPTS:-15} ${RETRY_DELAY_SECONDS:-1} psql --username "$DB_APP_USER" --dbname "$DB_APP_DATABASE" -c '\l'

exec "${@}"
