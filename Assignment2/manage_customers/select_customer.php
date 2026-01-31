<?php
// Customer search page - Student: Samar Khajuria, ID: T00714740
require_once __DIR__ . '/../includes/database.php';
include __DIR__ . '/../includes/header.php';

$matches = [];
$q = '';
// Handle search form submission
if (isset($_GET['lastName'])) {
  $q = trim($_GET['lastName'] ?? '');
  if ($q !== '') {
    // Search customers by last name (partial match)
    $stmt = $db->prepare("SELECT customerID, firstName, lastName, email FROM customers WHERE lastName LIKE ? ORDER BY firstName");
    $stmt->execute([$q.'%']);  // Add % for LIKE pattern matching
    $matches = $stmt->fetchAll();
  }
}
?>
<h2>Select Customer</h2>
<form method="get" id="aligned">
  <label>Last Name:</label><input name="lastName" value="<?= htmlspecialchars($q) ?>"><br>
  <button type="submit">Search</button>
  <a style="margin-left:1em;" href="customer_form.php?mode=add">Add Customer</a>
</form>

<?php if ($q !== ''): ?>
  <h3>Results</h3>
  <table>
    <tr><th>Name</th><th>Email</th><th>Action</th></tr>
    <?php foreach ($matches as $m): ?>
      <tr>
        <td><?= htmlspecialchars($m['firstName'].' '.$m['lastName']) ?></td>
        <td><?= htmlspecialchars($m['email']) ?></td>
        <td><a href="customer_form.php?mode=edit&id=<?= intval($m['customerID']) ?>">Select</a></td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
