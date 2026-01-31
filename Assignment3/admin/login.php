<?php
// Admin login page
// Administrators log in here to access admin functions
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/database.php';

// If already logged in, go to menu
if (($_SESSION['role'] ?? null) === 'admin' && !empty($_SESSION['admin'])) {
  redirect_to('admin/menu.php');
}

$errorMsg = null;

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');
  
  // Find admin by username
  $admin = db_one("SELECT * FROM administrators WHERE username = ?", [$username]);
  
  // Check password
  if ($admin && $admin['password'] === $password) {
    // Set session variables
    $_SESSION['role']  = 'admin';
    $_SESSION['admin'] = ['username' => $admin['username']];
    
    redirect_to('admin/menu.php');
  } else {
    $errorMsg = "Invalid username or password.";
  }
}

require __DIR__ . '/../includes/header.php';
?>
<h2>Admin Login</h2>
<?php if ($errorMsg): ?>
<p class="error"><?= htmlspecialchars($errorMsg) ?></p>
<?php endif; ?>

<form method="post">
  <table>
    <tr>
      <td><label for="username">Username:</label></td>
      <td><input type="text" id="username" name="username" value="admin" required></td>
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
