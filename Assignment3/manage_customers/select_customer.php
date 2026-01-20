<?php
// Customer search page
// Admin can search for customers by last name
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('admin');

$results = [];
$searchTerm = '';
if (isset($_GET['lastName'])) {
  $searchTerm = trim($_GET['lastName'] ?? '');
  if ($searchTerm !== '') {
    $results = db_all(
      "SELECT customerID, firstName, lastName, email
       FROM customers
       WHERE lastName LIKE ?
       ORDER BY firstName",
      [$searchTerm.'%']
    );
  }
}

require __DIR__ . '/../includes/header.php';
?>
<h2>Select Customer</h2>
<form method="get" id="aligned">
  <label>Last Name:</label><input name="lastName" value="<?= htmlspecialchars($searchTerm) ?>"><br>
  <button type="submit">Search</button>
</form>

<?php if ($searchTerm !== ''): ?>
  <h3>Results</h3>
  <?php if (!$results): ?>
    <p>No customers found.</p>
  <?php else: ?>
  <table>
    <tr><th>Name</th><th>Email</th><th>Action</th></tr>
    <?php foreach ($results as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['firstName'].' '.$r['lastName']) ?></td>
        <td><?= htmlspecialchars($r['email']) ?></td>
        <td><a href="customer_form.php?mode=edit&id=<?= intval($r['customerID']) ?>">Select</a></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <?php endif; ?>
<?php endif; ?>

<p><a href="../admin/menu.php">Admin Menu</a></p>
<?php include __DIR__ . '/../includes/footer.php'; ?>
