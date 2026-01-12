<?php
// Add technician form - Student: Samar Khajuria, ID: T00714740
require_once __DIR__ . '/../includes/database.php';
include __DIR__ . '/../includes/header.php';

$error = '';
// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get form data and trim whitespace
  $first = trim($_POST['firstName'] ?? '');
  $last  = trim($_POST['lastName'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $pass  = trim($_POST['password'] ?? '');

  // Validate each field with specific error messages
  if ($first === '') {
    $error = 'First Name is required.';
  } elseif ($last === '') {
    $error = 'Last Name is required.';
  } elseif ($email === '') {
    $error = 'Email is required.';
  } elseif ($phone === '') {
    $error = 'Phone is required.';
  } elseif ($pass === '') {
    $error = 'Password is required.';
  } else {
    // All fields valid, insert new technician
    $stmt = $db->prepare("INSERT INTO technicians (firstName, lastName, email, phone, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$first, $last, $email, $phone, $pass]);
    // Redirect to technician list
    header('Location: index.php');
    exit;
  }
}
?>
<h2>Add Technician</h2>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" id="aligned">
  <label>First Name:</label><input name="firstName" value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>"><br>
  <label>Last Name:</label><input name="lastName" value="<?= htmlspecialchars($_POST['lastName'] ?? '') ?>"><br>
  <label>Email:</label><input name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"><br>
  <label>Phone:</label><input name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"><br>
  <label>Password:</label><input name="password" type="password"><br>
  <button type="submit">Add Technician</button>
</form>
<p><a href="index.php">Back to List</a></p>
<?php include __DIR__ . '/../includes/footer.php'; ?>
