<?php
// Add new product form
// Allows admin to add products to the system
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('admin');

$err = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $code = trim($_POST['productCode'] ?? '');
  $name = trim($_POST['name'] ?? '');
  $version = trim($_POST['version'] ?? '');
  $dateStr = trim($_POST['releaseDate'] ?? '');

  // Validate fields
  if ($code === '' || $name === '' || $version === '' || $dateStr === '') {
    $err = 'A required field was not entered.';
  } else {
    // Parse date
    $ts = strtotime($dateStr);
    if ($ts === false) {
      $err = 'Invalid release date.';
    } else {
      $releaseDate = date('Y-m-d', $ts);
      
      // Insert product
      db_exec("INSERT INTO products (productCode, name, version, releaseDate) VALUES (?, ?, ?, ?)", 
        [$code, $name, $version, $releaseDate]);
      
      redirect_to('manage_products/index.php');
      exit;
    }
  }
}

require __DIR__ . '/../includes/header.php';
?>

<h2>Add Product</h2>

<?php if ($err): ?><p class="error"><?= htmlspecialchars($err) ?></p><?php endif; ?>

<form method="post" id="aligned">
  <label>Code:</label><input name="productCode" required><br>
  <label>Name:</label><input name="name" required><br>
  <label>Version:</label><input name="version" required><br>
  <label>Release Date:</label><input name="releaseDate" placeholder="YYYY-MM-DD or natural date" required><br>
  <button type="submit">Add Product</button>
</form>

<p><a href="<?= htmlspecialchars(app_url('manage_products/index.php')) ?>">Back</a></p>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
