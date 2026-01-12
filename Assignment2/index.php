<?php
// Main menu page - Student: Samar Khajuria, ID: T00714740
require_once __DIR__ . '/includes/database.php';
include __DIR__ . '/includes/header.php';
?>
<nav>
  <h2>Administrators</h2>
  <ul>
    <li><a href="manage_products/index.php">Manage Products</a></li>
    <li><a href="manage_technicians/index.php">Manage Technicians</a></li>
    <li><a href="manage_customers/select_customer.php">Manage Customers</a></li>
    <li><a href="create_incident/get_customer.php">Create Incident</a></li>
    <li><a href="under_construction.php">Assign Incident</a></li>
    <li><a href="under_construction.php">Display Incidents</a></li>
  </ul>

  <h2>Technicians</h2>
  <ul>
    <li><a href="under_construction.php">Update Incident</a></li>
  </ul>

  <h2>Customers</h2>
  <ul>
    <li><a href="register_product/customer_login.php">Register Product</a></li>
  </ul>
</nav>
<?php include __DIR__ . '/includes/footer.php'; ?>
