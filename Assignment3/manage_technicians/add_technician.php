<?php
// Add new technician form
// Allows admin to add technicians to the system
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('admin');

$err = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first = trim($_POST['firstName'] ?? '');
  $last  = trim($_POST['lastName'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $pass  = trim($_POST['password'] ?? '');

  // Validate required fields
  if ($first==='' || $last==='' || $email==='' || $phone==='') {
    $err = 'All fields except password are required.';
  } 
  // Check email format
  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $err = 'Please enter a valid email.';
  } 
  // Check phone format
  elseif (!preg_match('/^\(\d{3}\) \d{3}-\d{4}$/', $phone)) {
    $err = 'Phone must be in the (999) 999-9999 format.';
  } 
  else {
    // Insert technician
    db_exec("INSERT INTO technicians (firstName, lastName, email, phone, password)
       VALUES (?, ?, ?, ?, ?)",
       [$first, $last, $email, $phone, $pass === '' ? null : $pass]);
    
    redirect_to('manage_technicians/index.php');
    exit;
  }
}

require __DIR__ . '/../includes/header.php';
?>

<h2>Add Technician</h2>

<?php if ($err): ?><p class="error"><?= htmlspecialchars($err) ?></p><?php endif; ?>

<form method="post" id="aligned">
  <label>First Name:</label><input name="firstName" required><br>
  <label>Last Name:</label><input name="lastName" required><br>
  <label>Email:</label><input name="email" type="email" required><br>
  <label>Phone:</label><input name="phone" placeholder="(999) 999-9999" required><br>
  <label>Password:</label><input name="password" type="password" placeholder="(optional)"><br>
  <button type="submit">Add Technician</button>
</form>

<p><a href="<?= htmlspecialchars(app_url('manage_technicians/index.php')) ?>">Back</a></p>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
