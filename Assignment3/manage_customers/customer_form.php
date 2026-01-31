<?php
// Customer add/edit form
// Admin can add new customers or edit existing ones
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('admin');

// Get countries for dropdown
$countries = db_all("SELECT countryCode, countryName FROM countries ORDER BY countryName");

// Determine if we're adding or editing
$mode = $_GET['mode'] ?? 'edit';

// Load customer if editing
$customer = null;
if ($mode === 'edit') {
  $id = (int)($_GET['id'] ?? 0);
  if ($id > 0) {
    $customer = db_one("SELECT * FROM customers WHERE customerID = ?", [$id]);
  }
  if (!$customer) {
    $mode = 'add';
  }
}

$err = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get all form fields
  $fields = ['firstName','lastName','address','city','state','postalCode','email','phone','password','countryCode'];
  foreach ($fields as $f) { 
    $$f = trim($_POST[$f] ?? ''); 
  }

  // Helper function to check field length
  $checkLen = function($s,$min,$max){ 
    $n = strlen($s); 
    return ($n >= $min && $n <= $max); 
  };
  
  // Validate all fields
  if (!$checkLen($firstName,1,50) || !$checkLen($lastName,1,50) || !$checkLen($address,1,50) ||
      !$checkLen($city,1,50) || !$checkLen($state,1,50) || !$checkLen($postalCode,1,20) || !$checkLen($countryCode,2,2)) {
    $err = 'Please complete all required fields within length limits.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $err = 'Please enter a valid email address.';
  } elseif ($phone !== '' && !preg_match('/^\(\d{3}\) \d{3}-\d{4}$/', $phone)) {
    $err = 'Phone must be in the (999) 999-9999 format.';
  } else {
    // Save customer
    if ($mode === 'add') {
      db_exec("INSERT INTO customers (firstName,lastName,address,city,state,postalCode,countryCode,phone,email,password)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
       [$firstName,$lastName,$address,$city,$state,$postalCode,$countryCode,($phone ?: null),$email,($password ?: null)]);
      redirect_to('manage_customers/select_customer.php');
      exit;
    } else {
      db_exec("UPDATE customers
       SET firstName=?, lastName=?, address=?, city=?, state=?, postalCode=?, countryCode=?, phone=?, email=?, password=?
       WHERE customerID=?",
       [$firstName,$lastName,$address,$city,$state,$postalCode,$countryCode,($phone ?: null),$email,($password ?: null), (int)$customer['customerID']]);
      redirect_to('manage_customers/select_customer.php');
      exit;
    }
  }
}

require __DIR__ . '/../includes/header.php';
?>
<h2><?= $mode === 'add' ? 'Add Customer' : 'Edit Customer' ?></h2>

<?php if ($err): ?>
<p class="error"><?= htmlspecialchars($err) ?></p>
<?php endif; ?>

<form method="post" id="aligned">
  <label>First Name:</label>
  <input name="firstName" value="<?= htmlspecialchars($customer['firstName'] ?? '') ?>" required><br>
  
  <label>Last Name:</label>
  <input name="lastName" value="<?= htmlspecialchars($customer['lastName'] ?? '') ?>" required><br>

  <label>Address:</label>
  <input name="address" value="<?= htmlspecialchars($customer['address'] ?? '') ?>" required><br>
  
  <label>City:</label>
  <input name="city" value="<?= htmlspecialchars($customer['city'] ?? '') ?>" required><br>
  
  <label>State:</label>
  <input name="state" value="<?= htmlspecialchars($customer['state'] ?? '') ?>" required><br>
  
  <label>Postal Code:</label>
  <input name="postalCode" value="<?= htmlspecialchars($customer['postalCode'] ?? '') ?>" required><br>
  
  <label>Country:</label>
  <select name="countryCode" required>
    <option value="">-- Select --</option>
    <?php foreach ($countries as $c): ?>
      <option value="<?= htmlspecialchars($c['countryCode']) ?>"
        <?= (isset($customer['countryCode']) && $customer['countryCode']===$c['countryCode']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['countryName']) ?>
      </option>
    <?php endforeach; ?>
  </select><br>

  <label>Phone:</label>
  <input name="phone" placeholder="(999) 999-9999" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>"><br>
  
  <label>Email:</label>
  <input name="email" type="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" required><br>
  
  <label>Password:</label>
  <input name="password" type="password" value="<?= htmlspecialchars($customer['password'] ?? '') ?>"><br>
  
  <button type="submit"><?= $mode === 'add' ? 'Add Customer' : 'Save Changes' ?></button>
</form>

<p><a href="<?= htmlspecialchars(app_url('manage_customers/select_customer.php')) ?>">Back to Select Customer</a></p>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
