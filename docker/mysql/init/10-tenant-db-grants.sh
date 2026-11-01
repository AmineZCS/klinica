#!/bin/sh
set -e

# The app user needs rights over every per-tenant "klinica_*" database, not just
# the central one. Runs once on first volume init; on an existing volume, grant
# these by hand instead.
mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -e \
  "GRANT ALL PRIVILEGES ON \`klinica\\_%\`.* TO '${MYSQL_USER}'@'%'; FLUSH PRIVILEGES;"
