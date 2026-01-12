<?php 
// Header template - Student: Samar Khajuria, ID: T00714740
// Calculates relative paths for CSS and home link based on current script location

// Get the directory where the script that included this file is located
$script_dir = dirname($_SERVER['SCRIPT_FILENAME']);
$app_root = dirname(__DIR__);

// Calculate relative path from script directory to app root for CSS
$rel_path = '';
if ($script_dir !== $app_root) {
  // Normalize paths for comparison (handle Windows/Mac differences)
  $script_normalized = str_replace('\\', '/', $script_dir);
  $app_normalized = str_replace('\\', '/', $app_root);
  
  if (strpos($script_normalized, $app_normalized) === 0) {
    $diff = substr($script_normalized, strlen($app_normalized));
    $depth = substr_count($diff, '/');
    if ($depth > 0) {
      $rel_path = str_repeat('../', $depth);
    }
  }
}

// Build paths to CSS and home page (URLs always use forward slashes)
$css_path = $rel_path . 'main.css';
$home_path = $rel_path . 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>SportsPro Technical Support</title>
  <link rel="stylesheet" href="<?= htmlspecialchars($css_path) ?>" />
</head>
<body>
<header>
  <h1>SportsPro Technical Support</h1>
  <p>Sports management software for the sports enthusiast</p>
  <p></p>
  <p><a href="<?= htmlspecialchars($home_path) ?>" class="home-button">Home</a></p>
</header>
<main>
