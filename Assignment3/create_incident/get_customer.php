<?php
// Customer lookup page for incident creation
// Admin enters customer email to find customer before creating incident
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

// Make sure user is admin
require_role('admin');

$errorMsg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  
  if ($email !== '') {
    // Look up customer by email
    $customer = db_one("SELECT customerID, firstName, lastName FROM customers WHERE email = ?", [$email]);
    
    if ($customer) {
      // Store customer info in session for next step
      $_SESSION['ci_customer_id']   = (int)$customer['customerID'];
      $_SESSION['ci_customer_name'] = $customer['firstName'].' '.$customer['lastName'];
      
      // Go to incident creation form
      redirect_to('create_incident/create_incident.php');
      exit;
    } else {
      $errorMsg = 'Customer not found.';
    }
  } else {
    $errorMsg = 'Please enter an email.';
  }
}

require __DIR__ . '/../includes/header.php';
?>
<h2>Get Customer</h2>

<?php if ($errorMsg): ?>
<p class="error"><?= htmlspecialchars($errorMsg) ?></p>
<?php endif; ?>

<form method="post" id="aligned">
  <label>Email:</label>
  <input name="email" type="email" required><br>
  <button type="submit">Get Customer</button>
</form>

<p><a href="<?= htmlspecialchars(app_url('admin/menu.php')) ?>">Back to Admin Menu</a></p>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
