<?php
// Add product form - Student: Samar Khajuria, ID: T00714740
require_once __DIR__ . '/../includes/database.php';
include __DIR__ . '/../includes/header.php';

$error = '';
// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get and clean form data
  $code = trim($_POST['productCode'] ?? '');
  $name = trim($_POST['name'] ?? '');
  $ver  = trim($_POST['version'] ?? '');
  $date = trim($_POST['releaseDate'] ?? '');

  // Validate required fields with specific error messages
  if ($code === '') {
    $error = 'Product Code is required.';
  } elseif ($name === '') {
    $error = 'Name is required.';
  } elseif ($ver === '') {
    $error = 'Version is required.';
  } elseif (!is_numeric($ver)) {
    // Version must be a valid number (decimal allowed)
    $error = 'Version must be a valid number.';
  } elseif ($date === '') {
    $error = 'Release Date is required.';
  } else {
    // Parse date string and convert to MySQL date format
    $ts = strtotime($date);
    if ($ts === false) {
      $error = 'Invalid release date format. Please enter a valid date.';
    } else {
      // Convert to YYYY-MM-DD format for database
      $release = date('Y-m-d', $ts);
      // Insert new product using prepared statement
      // Version is stored as decimal, so convert string to float
      $stmt = $db->prepare("INSERT INTO products (productCode, name, version, releaseDate) VALUES (?, ?, ?, ?)");
      $stmt->execute([$code, $name, (float)$ver, $release]);
      // Redirect to product list after successful insert
      header('Location: index.php');
      exit;
    }
  }
}
?>
<h2>Add Product</h2>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" id="aligned">
  <label>Product Code:</label><input name="productCode" value="<?= htmlspecialchars($_POST['productCode'] ?? '') ?>"><br>
  <label>Name:</label><input name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"><br>
  <label>Version:</label><input name="version" type="number" step="0.1" value="<?= htmlspecialchars($_POST['version'] ?? '') ?>"><br>
  <label>Release Date:</label><input name="releaseDate" placeholder="Any valid date (e.g., 3/5/2017 or 2017-03-05)" value="<?= htmlspecialchars($_POST['releaseDate'] ?? '') ?>"><br>
  <button type="submit">Add Product</button>
</form>
<p><a href="index.php">View Product List</a></p>
<?php include __DIR__ . '/../includes/footer.php'; ?>
