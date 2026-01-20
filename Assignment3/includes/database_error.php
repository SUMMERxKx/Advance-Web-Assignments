<?php 
// Database error page template
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Database Error</title>
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('main.css')) ?>">
</head>
<body>
  <header><h1>SportsPro Technical Support</h1></header>
  <main>
    <h2>Database Error</h2>
    <p>Sorry, something went wrong while accessing the database.</p>
    <?php if (defined('APP_DEBUG') && APP_DEBUG && isset($error_message)): ?>
      <pre style="white-space:pre-wrap; border:1px solid #ccc; padding:8px; margin-top:10px; background:#f9f9f9;">
<?= htmlspecialchars($error_message) ?>
      </pre>
    <?php endif; ?>
  </main>
</body>
</html>
