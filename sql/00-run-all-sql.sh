#!/bin/bash

# On parcourt tous les fichiers .sql dans les sous-dossiers
# On les trie par nom pour respecter l'ordre chronologique de vos dossiers
find /docker-entrypoint-initdb.d -name "*.sql" | sort | while read -r file; do
    echo "Running $file..."
    mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" < "$file"
done