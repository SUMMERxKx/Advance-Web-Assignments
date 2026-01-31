<?php
// Display unassigned incidents
// Shows all incidents that haven't been assigned to a technician yet
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('admin');

// Get unassigned incidents with customer and product info
$incidents = db_all(
  "SELECT i.incidentID, i.dateOpened, i.title, i.description,
          CONCAT(c.firstName,' ',c.lastName) AS customerName,
          p.name AS productName
   FROM incidents i
   JOIN customers c ON i.customerID = c.customerID
   JOIN products  p ON i.productCode = p.productCode
   WHERE i.techID IS NULL
   ORDER BY i.dateOpened DESC"
);

require __DIR__ . '/../includes/header.php';
?>
<h2>Unassigned Incidents</h2>
<p><a href="assigned.php">View Assigned Incidents</a></p>

<?php if (!$incidents): ?>
  <p>No unassigned incidents.</p>
<?php else: ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Opened</th>
      <th>Customer</th>
      <th>Product</th>
      <th>Title</th>
      <th>Description</th>
    </tr>
    <?php foreach ($incidents as $inc): ?>
      <tr>
        <td><?= (int)$inc['incidentID'] ?></td>
        <td><?= htmlspecialchars(date('n-j-Y', strtotime($inc['dateOpened']))) ?></td>
        <td><?= htmlspecialchars($inc['customerName']) ?></td>
        <td><?= htmlspecialchars($inc['productName']) ?></td>
        <td><?= htmlspecialchars($inc['title']) ?></td>
        <td><?= htmlspecialchars($inc['description']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
