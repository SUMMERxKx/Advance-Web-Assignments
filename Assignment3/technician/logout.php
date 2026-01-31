<?php
// Technician logout
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
if (($_SESSION['role'] ?? null) === 'tech') {
  unset($_SESSION['tech'], $_SESSION['role']);
}
redirect_to('technician/login.php');
