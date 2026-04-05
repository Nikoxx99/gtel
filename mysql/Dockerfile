FROM mysql:8.0

# Importa automáticamente en primer arranque del volumen db_data
COPY all_databases.sql /docker-entrypoint-initdb.d/01-all_databases.sql
