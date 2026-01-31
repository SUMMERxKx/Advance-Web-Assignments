<?php
// Admin logout
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
if (($_SESSION['role'] ?? null) === 'admin') {
  unset($_SESSION['admin'], $_SESSION['role']);
}
redirect_to('admin/login.php');
