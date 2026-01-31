<?php
// Technician management page
// Lists all technicians and allows deletion
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('admin');

$msg = '';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  $id = (int)($_POST['delete_id'] ?? 0);
  if ($id > 0) {
    db_exec("DELETE FROM technicians WHERE techID = ?", [$id]);
    $msg = "Deleted technician #{$id}";
  }
}

// Get all technicians
$techs = db_all("SELECT techID, firstName, lastName, email, phone FROM technicians ORDER BY lastName, firstName");

require __DIR__ . '/../includes/header.php';
?>

<h2>Technician List</h2>

<p><a href="add_technician.php">Add Technician</a> | <a href="../admin/menu.php">Admin Menu</a></p>

<?php if ($msg): ?><p class="message"><?= htmlspecialchars($msg) ?></p><?php endif; ?>

<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Delete</th>
  </tr>
  <?php foreach ($techs as $t): ?>
    <tr>
      <td><?= (int)$t['techID'] ?></td>
      <td><?= htmlspecialchars($t['lastName'].', '.$t['firstName']) ?></td>
      <td><?= htmlspecialchars($t['email']) ?></td>
      <td><?= htmlspecialchars($t['phone']) ?></td>
      <td>
        <form method="post" onsubmit="return confirm('Delete this technician?');">
          <input type="hidden" name="delete_id" value="<?= (int)$t['techID'] ?>">
          <button type="submit">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
