<?php
// Database connection and query functions
// Handles all database operations with error handling
// 
// Author: Samar Khajuria
// Student ID: T00714740
// 
// NOTE: Database connection settings are configured in includes/config.php.
// If you need to change the host, port, username, or password,
// modify the DB_HOST, DB_PORT, DB_NAME, DB_USER, and DB_PASS constants in config.php.

// Load config if available
if (!defined('APP_DEBUG')) {
  $configFile = __DIR__ . '/config.php';
  if (is_file($configFile)) { require_once $configFile; }
  if (!defined('APP_DEBUG')) { define('APP_DEBUG', false); }
}

// Database connection settings
// These values are loaded from config.php for portability
// You can override them in config.php for different environments
$host = defined('DB_HOST') ? DB_HOST : 'localhost';
$port = defined('DB_PORT') && DB_PORT !== '' ? DB_PORT : null;
$dbname = defined('DB_NAME') ? DB_NAME : 'tech_support';
$username = defined('DB_USER') ? DB_USER : 'root';
$password = defined('DB_PASS') ? DB_PASS : '';

// Build DSN with optional port
$dsn = "mysql:host={$host}";
if ($port !== null) {
  $dsn .= ";port={$port}";
}
$dsn .= ";dbname={$dbname}";

// PDO configuration options
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

// Create database connection
try {
  $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
  $error_message = APP_DEBUG 
    ? "Database connection failed: " . $e->getMessage() . "\n\nPlease check your connection settings in includes/config.php"
    : "Database connection failed. Please check your connection settings in includes/config.php";
  include __DIR__ . '/database_error.php';
  exit;
}

/**
 * Run a SELECT query and return all matching rows
 * @param string $sql SQL query with ? placeholders
 * @param array $params Values to bind to placeholders
 * @return array Array of rows (each row is an associative array)
 */
function db_all(string $sql, array $params = []): array {
  global $db;
  try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
  } catch (PDOException $e) {
    $debugInfo = '';
    if (APP_DEBUG) {
      $debugInfo = "SQL: {$sql}\nParams: " . json_encode($params) . "\nError: " . $e->getMessage();
    }
    $error_message = APP_DEBUG ? "Query failed.\n{$debugInfo}" : 'A database error occurred.';
    include __DIR__ . '/database_error.php';
    exit;
  }
}

/**
 * Run a SELECT query and return the first row (or null if no results)
 * @param string $sql SQL query with ? placeholders
 * @param array $params Values to bind to placeholders
 * @return array|null Single row as associative array, or null
 */
function db_one(string $sql, array $params = []): ?array {
  $results = db_all($sql, $params);
  return $results[0] ?? null;
}

/**
 * Execute INSERT, UPDATE, or DELETE query
 * @param string $sql SQL query with ? placeholders
 * @param array $params Values to bind to placeholders
 * @return int Number of rows affected
 */
function db_exec(string $sql, array $params = []): int {
  global $db;
  try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
  } catch (PDOException $e) {
    $debugInfo = '';
    if (APP_DEBUG) {
      $debugInfo = "SQL: {$sql}\nParams: " . json_encode($params) . "\nError: " . $e->getMessage();
    }
    $error_message = APP_DEBUG ? "Exec failed.\n{$debugInfo}" : 'A database error occurred.';
    include __DIR__ . '/database_error.php';
    exit;
  }
}
?>
