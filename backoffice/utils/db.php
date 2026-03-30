<?php
declare(strict_types=1);

// Simple PDO MySQL connection helper.
// Reads configuration from environment variables with sensible defaults that match compose.yml.

function getPDO(): PDO
{
    $host = getenv('DB_HOST') ?: getenv('MYSQL_HOST') ?: 'article_db';
    $port = getenv('DB_PORT') ?: '3306';
    $db   = getenv('DB_NAME') ?: getenv('MYSQL_DATABASE') ?: 'article_db';
    $user = getenv('DB_USER') ?: getenv('MYSQL_USER') ?: 'user';
    $pass = getenv('DB_PASS') ?: getenv('MYSQL_PASSWORD') ?: 'password';
    $charset = 'utf8mb4';

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $db, $charset);

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        // Re-throw as RuntimeException to avoid leaking PDOException internals in some contexts.
        throw new RuntimeException('Database connection failed: ' . $e->getMessage(), (int)$e->getCode(), $e);
    }
}
