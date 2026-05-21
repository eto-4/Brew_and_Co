# ============================================================
# MySQL 8.0 database for BrewCo
# Imports initial schema from the project SQL file
# ============================================================
FROM mysql:8.0

ENV MYSQL_DATABASE=brewco
ENV MYSQL_USER=brewco_user
ENV MYSQL_PASSWORD=brewco_pass
ENV MYSQL_ROOT_PASSWORD=root_brewco_pass

# The project SQL file is copied here during build (see deploy.sh)
# MySQL automatically executes everything in /docker-entrypoint-initdb.d/
# on first startup (only if the volume is empty)
COPY brewco.sql /docker-entrypoint-initdb.d/01-brewco.sql

EXPOSE 3306