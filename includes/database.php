<?php
// Database connection for SportsPro Technical Support
// Student: Samar Khajuria, ID: T00714740

// Try multiple connection configurations to support different server setups
$attempts = [
  ['label' => 'MAMP: 127.0.0.1:8889 root/root', 'dsn' => 'mysql:host=127.0.0.1;port=8889;dbname=tech_support;charset=utf8mb4', 'user' => 'root', 'pass' => 'root'],
  ['label' => 'MAMP socket', 'dsn' => 'mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=tech_support;charset=utf8mb4', 'user' => 'root', 'pass' => 'root'],
  ['label' => 'Local: 127.0.0.1:3306 root/(empty)', 'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=tech_support;charset=utf8mb4', 'user' => 'root', 'pass' => ''],
];

// PDO options for error handling and data format
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Throw exceptions on errors
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Return associative arrays
];

$errors = [];
// Try each connection method until one works
foreach ($attempts as $cfg) {
  try {
    $db = new PDO($cfg['dsn'], $cfg['user'], $cfg['pass'], $options);
    break;  // Connection successful, stop trying
  } catch (PDOException $e) {
    // Save error message and try next configuration
    $errors[] = $cfg['label'] . ' → ' . $e->getMessage();
  }
}

// If no connection worked, show error and stop
if (!isset($db)) {
  header('Content-Type: text/plain; charset=utf-8');
  echo "Database connection failed. Tried:\n- " . implode("\n- ", $errors);
  exit;
}
?>
