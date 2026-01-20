<?php
// Product management page
// Lists all products and allows deletion
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('admin');

$msg = '';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_code'])) {
  $code = trim($_POST['delete_code'] ?? '');
  if ($code !== '') {
    db_exec("DELETE FROM products WHERE productCode = ?", [$code]);
    $msg = "Deleted product: " . htmlspecialchars($code);
  }
}

// Get all products
$products = db_all("SELECT productCode, name, version, releaseDate FROM products ORDER BY name");

require __DIR__ . '/../includes/header.php';
?>
<h2>Product List</h2>
<p><a href="<?= htmlspecialchars(app_url('manage_products/add_product.php')) ?>">Add Product</a> | <a href="<?= htmlspecialchars(app_url('admin/menu.php')) ?>">Admin Menu</a></p>

<?php if ($msg): ?>
<p class="message"><?= $msg ?></p>
<?php endif; ?>

<table>
  <tr>
    <th>Code</th>
    <th>Name</th>
    <th>Version</th>
    <th>Release Date</th>
    <th>Delete</th>
  </tr>
  <?php foreach ($products as $p): ?>
    <tr>
      <td><?= htmlspecialchars($p['productCode']) ?></td>
      <td><?= htmlspecialchars($p['name']) ?></td>
      <td><?= htmlspecialchars($p['version']) ?></td>
      <td>
        <?php
          $ts = strtotime($p['releaseDate']);
          echo $ts ? date('n-j-Y', $ts) : '';
        ?>
      </td>
      <td>
        <form method="post" onsubmit="return confirm('Delete this product?');">
          <input type="hidden" name="delete_code" value="<?= htmlspecialchars($p['productCode']) ?>">
          <button type="submit">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
