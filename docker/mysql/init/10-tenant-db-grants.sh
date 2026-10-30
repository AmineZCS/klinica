#!/bin/sh
set -e

# stancl/tenancy creates one database per tenant, named "klinica_<tenant_id>"
# (see the database.prefix key in config/tenancy.php). The application DB user
# therefore needs privileges to create and manage every "klinica_*" database,
# not just the central klinica_prod one.
#
# This runs once, on first initialization of the MySQL data volume, after the
# entrypoint has created ${MYSQL_USER}. On an already-initialized volume it does
# not run, so grant the same privileges by hand when adding it to a live server.
mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -e \
  "GRANT ALL PRIVILEGES ON \`klinica\\_%\`.* TO '${MYSQL_USER}'@'%'; FLUSH PRIVILEGES;"
