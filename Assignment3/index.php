<?php
// Main entry point for SportsPro Technical Support
// Provides links to different user login pages
// 
// Author: Samar Khajuria
// Student ID: T00714740

require __DIR__ . '/includes/header.php'; 
?>
  <h2>Main Menu</h2>
  
  <p><a href="<?= htmlspecialchars(app_url('admin/login.php')) ?>">Administrators</a></p>
  <p><a href="<?= htmlspecialchars(app_url('technician/login.php')) ?>">Technicians</a></p>
  <p><a href="<?= htmlspecialchars(app_url('register_product/customer_login.php')) ?>">Customers</a></p>

<?php 
include __DIR__ . '/includes/footer.php'; 
?>
