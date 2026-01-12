<?php
// Product registration page - Student: Samar Khajuria, ID: T00714740
session_start();
require_once __DIR__ . '/../includes/database.php';
// Check if customer is logged in
if (!isset($_SESSION['cust_id'])) { 
  header('Location: customer_login.php'); 
  exit; 
}
include __DIR__ . '/../includes/header.php';

// Get customer info from session
$customerID = (int)$_SESSION['cust_id'];
$name = htmlspecialchars($_SESSION['cust_name']);

// Get all available products for the dropdown
$products = $db->query("SELECT productCode, name FROM products ORDER BY name")->fetchAll();

$message = '';
$error = '';
// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $code = trim($_POST['productCode'] ?? '');
  
  if ($code !== '') {
    // Check if this product is already registered by this customer
    $check = $db->prepare("SELECT COUNT(*) as count FROM registrations WHERE customerID = ? AND productCode = ?");
    $check->execute([$customerID, $code]);
    $result = $check->fetch();
    
    if ($result['count'] > 0) {
      // Product already registered, show error
      $error = 'This product has already been registered.';
    } else {
      // Register the product with current date
      $stmt = $db->prepare("INSERT INTO registrations (customerID, productCode, registrationDate) VALUES (?, ?, CURDATE())");
      $stmt->execute([$customerID, $code]);
      $message = "The product was registered successfully. Code: ".htmlspecialchars($code);
    }
  }
}
?>
<h2>Register Product</h2>
<p>Welcome, <?= $name ?></p>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<?php if ($message): ?><p class="message"><?= htmlspecialchars($message) ?></p><?php endif; ?>

<form method="post" id="aligned">
  <label>Product:</label>
  <select name="productCode">
    <?php foreach ($products as $p): ?>
      <option value="<?= htmlspecialchars($p['productCode']) ?>"><?= htmlspecialchars($p['name']) ?></option>
    <?php endforeach; ?>
  </select><br>
  <button type="submit">Register Product</button>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
