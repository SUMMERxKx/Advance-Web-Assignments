<?php
// Customer add/edit form - Student: Samar Khajuria, ID: T00714740
require_once __DIR__ . '/../includes/database.php';
include __DIR__ . '/../includes/header.php';

// Load all countries for the dropdown menu
$countries = $db->query("SELECT countryCode, countryName FROM countries ORDER BY countryName")->fetchAll();

// Determine if we're adding or editing a customer
$mode = $_GET['mode'] ?? 'edit';
$customer = null;

// Load existing customer data if editing
if ($mode === 'edit') {
  $id = (int)($_GET['id'] ?? 0);
  $stmt = $db->prepare("SELECT * FROM customers WHERE customerID = ?");
  $stmt->execute([$id]);
  $customer = $stmt->fetch();
  
  // If customer not found, show error
  if (!$customer) { 
    echo "<p class='error'>Customer not found.</p>"; 
    include __DIR__ . '/../includes/footer.php'; 
    exit; 
  }
}

$error = '';
// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get all form fields and trim whitespace
  $fields = ['firstName','lastName','address','city','state','postalCode','email','phone','password','countryCode'];
  foreach ($fields as $f) { 
    $$f = trim($_POST[$f] ?? ''); 
  }

  // Helper function to check string length is within range
  $len_ok = function($s,$min,$max){ 
    $n = strlen($s); 
    return ($n >= $min && $n < $max+1); 
  };
  
  // Validate each field with specific error messages
  if (!$len_ok($firstName,1,50)) {
    $error = 'First Name is required and must be between 1 and 50 characters.';
  } elseif (!$len_ok($lastName,1,50)) {
    $error = 'Last Name is required and must be between 1 and 50 characters.';
  } elseif (!$len_ok($address,1,50)) {
    $error = 'Address is required and must be between 1 and 50 characters.';
  } elseif (!$len_ok($city,1,50)) {
    $error = 'City is required and must be between 1 and 50 characters.';
  } elseif (!$len_ok($state,1,50)) {
    $error = 'State is required and must be between 1 and 50 characters.';
  } elseif (!$len_ok($postalCode,1,20)) {
    $error = 'Postal Code is required and must be between 1 and 20 characters.';
  } elseif (!preg_match('/^\\(\\d{3}\\) \\d{3}-\\d{4}$/', $phone)) {
    // Validate phone format: (999) 999-9999
    $error = 'Phone must be in the (999) 999-9999 format.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Validate email format using PHP's built-in filter
    $error = 'Please enter a valid email address.';
  } elseif (!(strlen($password) >= 6 && strlen($password) < 21)) {
    // Password must be 6-20 characters
    $error = 'Password must be between 6 and 20 characters.';
  } else {
    // All validations passed, save to database
    if ($mode === 'add') {
      // Insert new customer
      $stmt = $db->prepare("INSERT INTO customers (firstName,lastName,address,city,state,postalCode,countryCode,phone,email,password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([$firstName,$lastName,$address,$city,$state,$postalCode,$countryCode,$phone,$email,$password]);
      header('Location: select_customer.php');
      exit;
    } else {
      // Update existing customer
      $id = (int)($_GET['id'] ?? 0);
      $stmt = $db->prepare("UPDATE customers SET firstName=?, lastName=?, address=?, city=?, state=?, postalCode=?, countryCode=?, phone=?, email=?, password=? WHERE customerID=?");
      $stmt->execute([$firstName,$lastName,$address,$city,$state,$postalCode,$countryCode,$phone,$email,$password,$id]);
      header('Location: select_customer.php');
      exit;
    }
  }

  // If validation failed, save form data to repopulate fields
  $customer = [
    'firstName'=>$firstName,'lastName'=>$lastName,'address'=>$address,'city'=>$city,'state'=>$state,
    'postalCode'=>$postalCode,'countryCode'=>$countryCode,'phone'=>$phone,'email'=>$email,'password'=>$password
  ];
}

// Set default country (Canada for new customers, existing country for editing)
$countryValue = $customer['countryCode'] ?? 'CA';
?>
<h2><?= $mode === 'add' ? 'Add Customer' : 'View/Update Customer' ?></h2>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

<form method="post" id="aligned">
  <label>First Name:</label><input name="firstName" value="<?= htmlspecialchars($customer['firstName'] ?? '') ?>"><br>
  <label>Last Name:</label><input name="lastName" value="<?= htmlspecialchars($customer['lastName'] ?? '') ?>"><br>
  <label>Address:</label><input name="address" value="<?= htmlspecialchars($customer['address'] ?? '') ?>"><br>
  <label>City:</label><input name="city" value="<?= htmlspecialchars($customer['city'] ?? '') ?>"><br>
  <label>State:</label><input name="state" value="<?= htmlspecialchars($customer['state'] ?? '') ?>"><br>
  <label>Postal Code:</label><input name="postalCode" value="<?= htmlspecialchars($customer['postalCode'] ?? '') ?>"><br>

  <label>Country:</label>
  <select name="countryCode">
    <?php foreach ($countries as $c): ?>
      <option value="<?= htmlspecialchars($c['countryCode']) ?>" <?= ($countryValue === $c['countryCode']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['countryName']) ?>
      </option>
    <?php endforeach; ?>
  </select><br>

  <label>Phone:</label><input name="phone" placeholder="(999) 999-9999" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>"><br>
  <label>Email:</label><input name="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>"><br>
  <label>Password:</label><input name="password" value="<?= htmlspecialchars($customer['password'] ?? '') ?>"><br>

  <button type="submit"><?= $mode === 'add' ? 'Add Customer' : 'Update Customer' ?></button>
</form>

<?php if ($mode === 'edit'): ?>
  <p><a href="select_customer.php">Search Customers</a></p>
<?php else: ?>
  <p><a href="select_customer.php">Back</a></p>
<?php endif; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
