<?php
// Product list page - Student: Samar Khajuria, ID: T00714740
require_once __DIR__ . '/../includes/database.php';
include __DIR__ . '/../includes/header.php';

// Handle product deletion when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_code'])) {
  $code = trim($_POST['delete_code']);
  if ($code !== '') {
    // Use prepared statement to safely delete product
    $stmt = $db->prepare("DELETE FROM products WHERE productCode = ?");
    $stmt->execute([$code]);
    echo "<p class='message'>Deleted product: " . htmlspecialchars($code) . "</p>";
  }
}

// Get all products ordered by name for display
$products = $db->query("SELECT productCode, name, version, releaseDate FROM products ORDER BY name")->fetchAll();
?>
<h2>Product List</h2>
<p><a href="add_product.php">Add Product</a></p>

<table>
  <tr>
    <th>Code</th>
    <th>Name</th>
    <th>Version</th>
    <th>Release Date</th>
    <th>&nbsp;</th>
  </tr>
  <?php foreach ($products as $p): ?>
    <tr>
      <td><?= htmlspecialchars($p['productCode']) ?></td>
      <td><?= htmlspecialchars($p['name']) ?></td>
      <td><?= htmlspecialchars($p['version']) ?></td>
      <td>
        <?php
          // Format date as m-d-Y format (no leading zeros for month/day)
          $ts = strtotime($p['releaseDate']);
          echo $ts ? date('n-j-Y', $ts) : '';
        ?>
      </td>
      <td>
        <form method="post" class="delete-form" data-code="<?= htmlspecialchars($p['productCode']) ?>" data-name="<?= htmlspecialchars($p['name']) ?>">
          <input type="hidden" name="delete_code" value="<?= htmlspecialchars($p['productCode']) ?>">
          <button type="submit">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<script>
// Handle delete confirmation with custom dialog
document.querySelectorAll('.delete-form').forEach(function(form) {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const code = this.dataset.code;
    const name = this.dataset.name;
    showConfirm('Are you sure you want to delete product "' + name + '" (Code: ' + code + ')?', function() {
      form.submit();
    });
  });
});
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
