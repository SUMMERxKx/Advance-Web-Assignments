<?php
// Customer login page
// Customers log in here to register products
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/database.php';

// If already logged in, go to menu
if (($_SESSION['role'] ?? null) === 'customer' && !empty($_SESSION['customer'])) {
  redirect_to('customer/menu.php');
}

$errorMsg = null;

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');

  // Find customer by email
  $customer = db_one("SELECT * FROM customers WHERE email = ?", [$email]);
  
  // Check password
  if ($customer && (!isset($customer['password']) || $customer['password'] === $password)) {
    // Set session variables
    $_SESSION['role'] = 'customer';
    $_SESSION['customer'] = [
      'customerID' => $customer['customerID'],
      'firstName'  => $customer['firstName'],
      'lastName'   => $customer['lastName'],
      'email'      => $customer['email'],
    ];
    redirect_to('customer/menu.php');
  } else {
    $errorMsg = "Invalid email or password.";
  }
}

require __DIR__ . '/../includes/header.php';
?>
<h2>Customer Login</h2>
<p>You must login before you can register a product</p>

<?php if ($errorMsg): ?>
<p class="error"><?= htmlspecialchars($errorMsg) ?></p>
<?php endif; ?>

<form method="post">
  <table>
    <tr>
      <td><label for="email">Email:</label></td>
      <td><input type="email" id="email" name="email" value="kelly@example.com" required></td>
    </tr>
    <tr>
      <td><label for="password">Password:</label></td>
      <td><input type="password" id="password" name="password" required></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" value="Login"></td>
    </tr>
  </table>
</form>
<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
