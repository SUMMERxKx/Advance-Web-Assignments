<?php
// Customer login for product registration - Student: Samar Khajuria, ID: T00714740
session_start();
require_once __DIR__ . '/../includes/database.php';
include __DIR__ . '/../includes/header.php';

$error = '';
// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  // Look up customer by email address
  $stmt = $db->prepare("SELECT customerID, firstName, lastName FROM customers WHERE email = ?");
  $stmt->execute([$email]);
  $cust = $stmt->fetch();
  
  if ($cust) {
    // Customer found, store in session and redirect to registration page
    $_SESSION['cust_id'] = (int)$cust['customerID'];
    $_SESSION['cust_name'] = $cust['firstName'].' '.$cust['lastName'];
    header('Location: register_product.php'); 
    exit;
  } else {
    // Email not found
    $error = 'Email not found.';
  }
}
?>
<h2>Customer Login</h2>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" id="aligned">
  <label>Email:</label><input name="email"><br>
  <button type="submit">Login</button>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
