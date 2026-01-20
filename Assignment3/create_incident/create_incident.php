<?php
// Incident creation form
// Admin creates new incident for selected customer
// Only shows products that customer has registered
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

// Make sure user is admin
require_role('admin');

// Check if customer was selected
if (!isset($_SESSION['ci_customer_id'])) {
  redirect_to('create_incident/get_customer.php');
  exit;
}

$customerID = (int)$_SESSION['ci_customer_id'];
$customerName = htmlspecialchars($_SESSION['ci_customer_name'] ?? '');

// Get products this customer has registered
$products = db_all("
  SELECT p.productCode, p.name
  FROM products p
  INNER JOIN registrations r ON r.productCode = p.productCode
  WHERE r.customerID = ?
  ORDER BY p.name
", [$customerID]);

$successMsg = '';
$errorMsg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $productCode = trim($_POST['productCode'] ?? '');
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');

  // Validate required fields
  if ($productCode === '' || $title === '' || $description === '') {
    $errorMsg = 'All fields are required.';
  } else {
    // Insert new incident
    db_exec("INSERT INTO incidents (customerID, productCode, dateOpened, title, description)
             VALUES (?, ?, NOW(), ?, ?)", [$customerID, $productCode, $title, $description]);
    $successMsg = 'Incident added to the database.';
  }
}

require __DIR__ . '/../includes/header.php';
?>
<h2>Create Incident</h2>
<p>Customer: <strong><?= $customerName ?></strong></p>

<?php if ($successMsg): ?>
<p class="message"><?= htmlspecialchars($successMsg) ?></p>
<?php endif; ?>

<?php if ($errorMsg): ?>
<p class="error"><?= htmlspecialchars($errorMsg) ?></p>
<?php endif; ?>

<form method="post" id="aligned">
  <label>Product:</label>
  <select name="productCode" required>
    <option value="">-- Select --</option>
    <?php foreach ($products as $product): ?>
      <option value="<?= htmlspecialchars($product['productCode']) ?>">
        <?= htmlspecialchars($product['name']) ?>
      </option>
    <?php endforeach; ?>
  </select><br>
  
  <label>Title:</label>
  <input name="title" required><br>
  
  <label>Description:</label>
  <input name="description" required><br>
  
  <button type="submit">Create Incident</button>
</form>

<p><a href="<?= htmlspecialchars(app_url('create_incident/get_customer.php')) ?>">Back</a></p>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
