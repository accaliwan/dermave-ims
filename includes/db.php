<?php
/**
 * DermAve IMS - PDO database connection helper.
 *
 * Returns a PDO instance when the database is reachable,
 * or null when it is not (so the rest of the app can fall back
 * to the in-memory sample data in data.php).
 */

require_once __DIR__ . '/config.php';

/**
 * @return PDO|null
 */
function db(): ?PDO
{
    static $pdo = null;
    static $tried = false;

    if ($tried) {
        return $pdo;
    }
    $tried = true;

    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (Throwable $e) {
        // Database not available yet – keep using sample data.
        $pdo = null;
    }

    return $pdo;
}
