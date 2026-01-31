<?php
// Technician menu page
// Shows options for technicians
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';

// Check if logged in as technician
if (($_SESSION['role'] ?? null) !== 'tech') { 
  redirect_to('technician/login.php'); 
}

require __DIR__ . '/../includes/header.php';
?>
<h2>Technician Menu</h2>

<p><a href="<?= htmlspecialchars(app_url('technician/select_incident.php')) ?>">View My Open Incidents</a></p>

<h3>Login Status</h3>
<p>You are logged in as <?= htmlspecialchars($_SESSION['tech']['name']) ?></p>

<form action="<?= htmlspecialchars(app_url('technician/logout.php')) ?>" method="post">
  <input type="submit" value="Logout">
</form>
<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
