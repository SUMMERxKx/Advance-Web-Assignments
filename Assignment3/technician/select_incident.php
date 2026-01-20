<?php
// Technician incident selection page
// Shows open incidents assigned to this technician
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('tech');

$techID = $_SESSION['tech']['techID'];

// Get open incidents for this technician
$incidents = db_all(
  "SELECT i.incidentID, i.dateOpened, i.title, i.description, p.name AS productName
   FROM incidents i
   JOIN products p ON i.productCode = p.productCode
   WHERE i.techID = ? AND i.dateClosed IS NULL
   ORDER BY i.dateOpened DESC", [$techID]
);

require __DIR__ . '/../includes/header.php';
?>
<h2>Select Incident</h2>

<?php if (!$incidents): ?>
  <p>There are no open incidents for this technician.</p>
  <p><a href="<?= htmlspecialchars(app_url('technician/select_incident.php')) ?>">Refresh List of Incidents</a></p>
  
  <p>You are logged in as <?= htmlspecialchars($_SESSION['tech']['email']) ?></p>
  
  <form action="<?= htmlspecialchars(app_url('technician/logout.php')) ?>" method="post">
    <input type="submit" value="Logout">
  </form>
  
<?php else: ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Opened</th>
      <th>Product</th>
      <th>Title</th>
      <th></th>
    </tr>
    <?php foreach ($incidents as $inc): ?>
      <tr>
        <td><?= (int)$inc['incidentID'] ?></td>
        <td><?= htmlspecialchars(date('n-j-Y', strtotime($inc['dateOpened']))) ?></td>
        <td><?= htmlspecialchars($inc['productName']) ?></td>
        <td><?= htmlspecialchars($inc['title']) ?></td>
        <td>
          <form method="post" action="<?= htmlspecialchars(app_url('technician/update_incident.php')) ?>">
            <input type="hidden" name="incidentID" value="<?= (int)$inc['incidentID'] ?>">
            <input type="submit" value="Select">
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
  
  <p>You are logged in as <?= htmlspecialchars($_SESSION['tech']['email']) ?></p>
  <form action="<?= htmlspecialchars(app_url('technician/logout.php')) ?>" method="post">
    <input type="submit" value="Logout">
  </form>
<?php endif; ?>
<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
