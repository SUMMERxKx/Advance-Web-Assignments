<?php
// Technician list page - Student: Samar Khajuria, ID: T00714740
require_once __DIR__ . '/../includes/database.php';
include __DIR__ . '/../includes/header.php';

// Handle technician deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  $id = (int)$_POST['delete_id'];
  if ($id > 0) {
    // Delete technician using prepared statement
    $stmt = $db->prepare("DELETE FROM technicians WHERE techID = ?");
    $stmt->execute([$id]);
    echo "<p class='message'>Deleted technician #".intval($id)."</p>";
  }
}

// Get all technicians ordered by last name, then first name
$rows = $db->query("SELECT techID, firstName, lastName, email, phone FROM technicians ORDER BY lastName, firstName")->fetchAll();
?>
<h2>Technician List</h2>
<p><a href="add_technician.php">Add Technician</a></p>

<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>&nbsp;</th>
  </tr>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= intval($r['techID']) ?></td>
      <td><?= htmlspecialchars($r['lastName'].', '.$r['firstName']) ?></td>
      <td><?= htmlspecialchars($r['email']) ?></td>
      <td><?= htmlspecialchars($r['phone']) ?></td>
      <td>
        <form method="post" class="delete-form" data-id="<?= intval($r['techID']) ?>" data-name="<?= htmlspecialchars($r['firstName'].' '.$r['lastName']) ?>">
          <input type="hidden" name="delete_id" value="<?= intval($r['techID']) ?>">
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
    const id = this.dataset.id;
    const name = this.dataset.name;
    showConfirm('Are you sure you want to delete technician "' + name + '" (ID: ' + id + ')?', function() {
      form.submit();
    });
  });
});
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
