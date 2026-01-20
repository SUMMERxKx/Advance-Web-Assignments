<?php
// Customer menu page
// Shows different options based on login status
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/header.php';

$isLoggedIn = (($_SESSION['role'] ?? null) === 'customer') && !empty($_SESSION['customer']);
?>
<h2>Customer Menu</h2>

<?php if ($isLoggedIn): ?>
  <p><a href="<?= htmlspecialchars(app_url('register_product/register_product.php')) ?>">Register a Product</a></p>
  
  <h3>Login Status</h3>
  <p>You are logged in as <?= htmlspecialchars($_SESSION['customer']['email']) ?></p>
  
  <form action="<?= htmlspecialchars(app_url('register_product/logout.php')) ?>" method="post">
    <input type="submit" value="Logout">
  </form>
  
<?php else: ?>
  <p><a href="<?= htmlspecialchars(app_url('register_product/customer_login.php')) ?>">Customer Login</a></p>
<?php endif; ?>
<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
