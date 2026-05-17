#!/bin/bash
set -e

HOST="${POSTGRES_HOST:-shared-postgres-db}"
SUPERUSER="${POSTGRES_USER:-postgres}"
PASS="${ELECTRICIDAD_DB_PASS:-electricidad_pass}"

until PGPASSWORD="$POSTGRES_PASSWORD" pg_isready -h "$HOST" -U "$SUPERUSER" -q; do
  echo "Waiting for postgres..."
  sleep 2
done

PGPASSWORD="$POSTGRES_PASSWORD" psql -h "$HOST" -U "$SUPERUSER" -d postgres <<-EOSQL
	DO \$\$ BEGIN
	  CREATE USER electricidad WITH ENCRYPTED PASSWORD '$PASS';
	EXCEPTION WHEN duplicate_object THEN NULL;
	END \$\$;

	SELECT 'CREATE DATABASE electricidad OWNER electricidad'
	WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'electricidad')\gexec

	GRANT ALL PRIVILEGES ON DATABASE electricidad TO electricidad;
EOSQL

PGPASSWORD="$POSTGRES_PASSWORD" psql -h "$HOST" -U "$SUPERUSER" -d electricidad \
  -c "GRANT ALL ON SCHEMA public TO electricidad;"

echo "electricidad database ready."
