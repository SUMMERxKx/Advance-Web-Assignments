<?php
// Get customer for incident creation - Student: Samar Khajuria, ID: T00714740
session_start();
require_once __DIR__ . '/../includes/database.php';
include __DIR__ . '/../includes/header.php';

$error = '';
// Process customer lookup form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  // Find customer by email
  $stmt = $db->prepare("SELECT customerID, firstName, lastName FROM customers WHERE email = ?");
  $stmt->execute([$email]);
  $cust = $stmt->fetch();
  
  if ($cust) {
    // Customer found, store in session and go to incident creation
    $_SESSION['ci_customer_id'] = (int)$cust['customerID'];
    $_SESSION['ci_customer_name'] = $cust['firstName'].' '.$cust['lastName'];
    header('Location: create_incident.php'); 
    exit;
  } else {
    // Customer not found
    $error = 'Customer not found.';
  }
}
?>
<h2>Get Customer</h2>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" id="aligned">
  <label>Email:</label><input name="email"><br>
  <button type="submit">Get Customer</button>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
