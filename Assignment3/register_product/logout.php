<?php
// Customer logout
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
if (($_SESSION['role'] ?? null) === 'customer') {
  unset($_SESSION['customer'], $_SESSION['role']);
}
redirect_to('register_product/customer_login.php');
