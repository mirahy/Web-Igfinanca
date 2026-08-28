#!/usr/bin/env bash

# O container mysql só cria automaticamente o banco de MYSQL_DATABASE
# (adb_mtz, a matriz) — as filiais (adb_vla, adb_sed) são bancos à parte
# na mesma conexão MySQL (mesmo host/porta, ver config/database.php) e
# precisam ser criados aqui, no bootstrap inicial do container.

mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS adb_vla;
    CREATE DATABASE IF NOT EXISTS adb_sed;
EOSQL

if [ -n "$MYSQL_USER" ]; then
mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    GRANT ALL PRIVILEGES ON \`adb_%\`.* TO '$MYSQL_USER'@'%';
    FLUSH PRIVILEGES;
EOSQL
fi
