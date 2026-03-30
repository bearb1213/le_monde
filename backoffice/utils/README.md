DB helper
==============

Usage
-----

This folder contains a small PDO helper to create a MySQL connection using environment variables.

Environment variables used (defaults shown mirror compose.yml):

- DB_HOST / MYSQL_HOST: article_db
- DB_PORT: 3306
- DB_NAME / MYSQL_DATABASE: article_db
- DB_USER / MYSQL_USER: user
- DB_PASS / MYSQL_PASSWORD: password

Example (from PHP):

```php
require __DIR__ . '/db.php';

try {
    $pdo = getPDO();
    $stmt = $pdo->query('SELECT 1');
    var_dump($stmt->fetch());
} catch (Exception $e) {
    echo 'DB error: ' . $e->getMessage();
}
```

Notes
-----
- When running in Docker with the provided compose.yml, the web service can connect to the DB using host `article_db` and port `3306`.
- If you run MySQL on the host machine and use the published port, set `DB_HOST=host.docker.internal` (Linux users may need to use `127.0.0.1` and port `3307`).
