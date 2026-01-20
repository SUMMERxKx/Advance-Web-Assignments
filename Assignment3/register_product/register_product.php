<?php
// Product registration page
// Customers can register products here
// Uses session to skip login if already logged in
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

// Check if customer is logged in
if (($_SESSION['role'] ?? null) !== 'customer' || empty($_SESSION['customer'])) {
  redirect_to('register_product/customer_login.php');
  exit;
}

$customerID = $_SESSION['customer']['customerID'];
$message = null;

// Handle registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $productCode = $_POST['productCode'] ?? '';
  
  if ($productCode) {
    // Register product (INSERT IGNORE prevents duplicates)
    db_exec("INSERT IGNORE INTO registrations (customerID, productCode, registrationDate)
             VALUES (?, ?, NOW())", [$customerID, $productCode]);
    
    $message = "Product registered successfully (Code: " . htmlspecialchars($productCode) . ").";
  }
}

// Get all products for dropdown
$products = db_all("SELECT productCode, name FROM products ORDER BY name");

require __DIR__ . '/../includes/header.php';
?>
<h2>Register Product</h2>

<?php if ($message): ?>
<p class="success"><?= $message ?></p>
<?php endif; ?>

<form method="post">
  <table>
    <tr>
      <td><label for="customer">Customer:</label></td>
      <td><?= htmlspecialchars($_SESSION['customer']['firstName'] . ' ' . $_SESSION['customer']['lastName']) ?></td>
    </tr>
    <tr>
      <td><label for="productCode">Product:</label></td>
      <td>
        <select name="productCode" id="productCode" required>
          <option value="">-- Select a Product --</option>
          <?php foreach ($products as $p): ?>
            <option value="<?= htmlspecialchars($p['productCode']) ?>">
              <?= htmlspecialchars($p['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" value="Register Product"></td>
    </tr>
  </table>
</form>

<p>You are logged in as <?= htmlspecialchars($_SESSION['customer']['email']) ?></p>

<form action="<?= htmlspecialchars(app_url('register_product/logout.php')) ?>" method="post">
  <input type="submit" value="Logout">
</form>
<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
