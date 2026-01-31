<?php
/*
 * Database Connection & Helper Functions
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * DATABASE CONFIGURATION - MODIFY THESE THREE LINES TO MATCH YOUR SERVER
 */

$dsn = 'mysql:host=localhost;dbname=recipe_planner';
$username = 'root';
$password = 'root';

// ============================================================================

$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
  $error_message = $e->getMessage();
  include __DIR__ . '/database_error.php';
  exit;
}

/*
 * Runs a SELECT query and returns all rows
 */
function db_query($sql, $params = [])
{
  global $db;
  try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
  } catch (PDOException $e) {
    $error_message = $e->getMessage();
    include __DIR__ . '/database_error.php';
    exit;
  }
}

/*
 * Runs a SELECT query and returns one row (or false)
 */
function db_query_one($sql, $params = [])
{
  global $db;
  try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
  } catch (PDOException $e) {
    $error_message = $e->getMessage();
    include __DIR__ . '/database_error.php';
    exit;
  }
}

/*
 * Runs INSERT/UPDATE/DELETE query
 * Returns the number of affected rows
 */
function db_execute($sql, $params = [])
{
  global $db;
  try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
  } catch (PDOException $e) {
    $error_message = $e->getMessage();
    include __DIR__ . '/database_error.php';
    exit;
  }
}

/*
 * Returns the last inserted ID
 */
function db_last_id()
{
  global $db;
  return $db->lastInsertId();
}
?>
