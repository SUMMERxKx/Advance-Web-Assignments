<?php
// Display assigned incidents
// Shows all incidents that have been assigned to technicians
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('admin');

// Get assigned incidents with customer, product, and technician info
$incidents = db_all(
  "SELECT i.incidentID, i.dateOpened, i.title, i.description, i.dateClosed,
          CONCAT(c.firstName,' ',c.lastName) AS customerName,
          p.name AS productName,
          CONCAT(t.firstName,' ',t.lastName) AS techName
   FROM incidents i
   JOIN customers   c ON i.customerID = c.customerID
   JOIN products    p ON i.productCode = p.productCode
   JOIN technicians t ON i.techID = t.techID
   WHERE i.techID IS NOT NULL
   ORDER BY i.dateOpened DESC"
);

require __DIR__ . '/../includes/header.php';
?>
<h2>Assigned Incidents</h2>
<p><a href="unassigned.php">View Unassigned Incidents</a></p>

<?php if (!$incidents): ?>
  <p>No assigned incidents.</p>
<?php else: ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Opened</th>
      <th>Customer</th>
      <th>Product</th>
      <th>Technician</th>
      <th>Title</th>
      <th>Description</th>
      <th>Closed</th>
    </tr>
    <?php foreach ($incidents as $inc): ?>
      <tr>
        <td><?= (int)$inc['incidentID'] ?></td>
        <td><?= htmlspecialchars(date('n-j-Y', strtotime($inc['dateOpened']))) ?></td>
        <td><?= htmlspecialchars($inc['customerName']) ?></td>
        <td><?= htmlspecialchars($inc['productName']) ?></td>
        <td><?= htmlspecialchars($inc['techName']) ?></td>
        <td><?= htmlspecialchars($inc['title']) ?></td>
        <td><?= htmlspecialchars($inc['description']) ?></td>
        <td><?= $inc['dateClosed'] ? htmlspecialchars(date('n-j-Y', strtotime($inc['dateClosed']))) : 'OPEN' ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
