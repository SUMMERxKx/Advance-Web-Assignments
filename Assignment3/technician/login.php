<?php
// Technician login page
// Technicians log in here to update incidents
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/database.php';

// If already logged in, go to menu
if (($_SESSION['role'] ?? null) === 'tech' && !empty($_SESSION['tech'])) {
  redirect_to('technician/menu.php');
}

$errorMsg = null;

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');
  
  // Find technician by email
  $tech = db_one("SELECT * FROM technicians WHERE email = ?", [$email]);
  
  // Check password
  if ($tech && (!isset($tech['password']) || $tech['password'] === $password)) {
    // Set session variables
    $_SESSION['role'] = 'tech';
    $_SESSION['tech'] = [
      'techID' => $tech['techID'],
      'name'   => $tech['firstName'].' '.$tech['lastName'],
      'email'  => $tech['email']
    ];
    redirect_to('technician/menu.php');
  } else {
    $errorMsg = "Invalid email or password.";
  }
}

require __DIR__ . '/../includes/header.php';
?>
<h2>Technician Login</h2>
<p>You must login before you can update an incident.</p>

<?php if ($errorMsg): ?>
<p class="error"><?= htmlspecialchars($errorMsg) ?></p>
<?php endif; ?>

<form method="post">
  <table>
    <tr>
      <td><label for="email">Email:</label></td>
      <td><input type="email" id="email" name="email" value="gunter@sportspro.com" required></td>
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
