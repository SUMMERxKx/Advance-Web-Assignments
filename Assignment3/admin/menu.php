<?php
// Admin menu page
// Main dashboard for administrators
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';

require_role('admin');

require __DIR__ . '/../includes/header.php';
?>
<h2>Admin Menu</h2>

<p><a href="<?= htmlspecialchars(app_url('manage_products/')) ?>">Manage Products</a></p>
<p><a href="<?= htmlspecialchars(app_url('manage_technicians/')) ?>">Manage Technicians</a></p>
<p><a href="<?= htmlspecialchars(app_url('manage_customers/select_customer.php')) ?>">Manage Customers</a></p>
<p><a href="<?= htmlspecialchars(app_url('create_incident/get_customer.php')) ?>">Create Incident</a></p>
<p><a href="<?= htmlspecialchars(app_url('assign_incident/select_incident.php')) ?>">Assign Incident</a></p>
<p><a href="<?= htmlspecialchars(app_url('display_incidents/unassigned.php')) ?>">Display Incidents</a></p>

<h3>Login Status</h3>
<p>You are logged in as admin</p>

<form action="<?= htmlspecialchars(app_url('admin/logout.php')) ?>" method="post">
  <input type="submit" value="Logout">
</form>
<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
