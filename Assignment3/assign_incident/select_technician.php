<?php
// Select technician for incident assignment
// Shows all technicians with their current workload
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('admin');

// Store incident ID in session from POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $_SESSION['assign'] = $_SESSION['assign'] ?? [];
  $_SESSION['assign']['incidentID'] = (int)($_POST['incidentID'] ?? 0);
}

$incidentID = $_SESSION['assign']['incidentID'] ?? 0;

if (!$incidentID) { 
  redirect_to('assign_incident/select_incident.php'); 
  exit; 
}

// Get technicians with open incident count using correlated subquery
$technicians = db_all(
  "SELECT t.techID, t.firstName, t.lastName, t.email,
          (SELECT COUNT(*) FROM incidents i WHERE i.techID = t.techID AND i.dateClosed IS NULL) AS openCount
   FROM technicians t
   ORDER BY t.lastName, t.firstName"
);

require __DIR__ . '/../includes/header.php';
?>
<h2>Select Technician</h2>
<p>Incident #<?= (int)$incidentID ?></p>

<table>
  <tr>
    <th>Technician</th>
    <th>Email</th>
    <th>Open Incidents</th>
    <th></th>
  </tr>
  <?php foreach ($technicians as $tech): ?>
    <tr>
      <td><?= htmlspecialchars($tech['firstName'].' '.$tech['lastName']) ?></td>
      <td><?= htmlspecialchars($tech['email']) ?></td>
      <td><?= (int)$tech['openCount'] ?></td>
      <td>
        <form method="post" action="<?= htmlspecialchars(app_url('assign_incident/assign_incident.php')) ?>">
          <input type="hidden" name="techID" value="<?= (int)$tech['techID'] ?>">
          <button>Select</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
