<?php
// Create incident page - Student: Samar Khajuria, ID: T00714740
session_start();
require_once __DIR__ . '/../includes/database.php';
// Make sure customer was selected first
if (!isset($_SESSION['ci_customer_id'])) { 
  header('Location: get_customer.php'); 
  exit; 
}
include __DIR__ . '/../includes/header.php';

// Get customer info from session
$customerID = (int)$_SESSION['ci_customer_id'];
$name = htmlspecialchars($_SESSION['ci_customer_name']);

// Get only products that this customer has registered
// Use JOIN to filter products by customer registrations
$stmt = $db->prepare("
  SELECT p.productCode, p.name
  FROM products p
  INNER JOIN registrations r ON r.productCode = p.productCode
  WHERE r.customerID = ?
  ORDER BY p.name
");
$stmt->execute([$customerID]);
$products = $stmt->fetchAll();

$error = '';
$message = '';
// Process incident creation form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $code = trim($_POST['productCode'] ?? '');
  $title = trim($_POST['title'] ?? '');
  $desc  = trim($_POST['description'] ?? '');

  // Validate required fields
  if ($code === '' || $title === '' || $desc === '') {
    $error = 'All fields are required.';
  } else {
    // Create incident with current timestamp
    $ins = $db->prepare("INSERT INTO incidents (customerID, productCode, dateOpened, title, description) VALUES (?, ?, NOW(), ?, ?)");
    $ins->execute([$customerID, $code, $title, $desc]);
    $message = 'Incident added to the database.';
  }
}
?>
<h2>Create Incident</h2>
<p>Customer: <?= $name ?></p>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<?php if ($message): ?><p class="message"><?= htmlspecialchars($message) ?></p><?php endif; ?>

<form method="post" id="aligned">
  <label>Product:</label>
  <select name="productCode">
    <?php foreach ($products as $p): ?>
      <option value="<?= htmlspecialchars($p['productCode']) ?>"><?= htmlspecialchars($p['name']) ?></option>
    <?php endforeach; ?>
  </select><br>
  <label>Title:</label><input name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"><br>
  <label>Description:</label><textarea name="description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea><br>
  <button type="submit">Create Incident</button>
</form>
<p><a href="get_customer.php">Back</a></p>
<?php include __DIR__ . '/../includes/footer.php'; ?>
