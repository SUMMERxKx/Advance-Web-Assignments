<?php
// Complete the incident assignment
// Updates the incident with the selected technician
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('admin');

// Get incident ID from session
$incidentID = $_SESSION['assign']['incidentID'] ?? 0;

// Get technician ID from POST and store in session
$techID = (int)($_POST['techID'] ?? 0);
if ($techID) {
  $_SESSION['assign'] = $_SESSION['assign'] ?? [];
  $_SESSION['assign']['techID'] = $techID;
}

$success = false;

// Update incident if both IDs are valid
if ($incidentID && $techID) {
  $success = db_exec("UPDATE incidents SET techID = ? WHERE incidentID = ?", [$techID, $incidentID]) > 0;
}

require __DIR__ . '/../includes/header.php';
?>
<h2>Assign Incident</h2>

<?php if ($success): ?>
  <p class="success">Incident #<?= (int)$incidentID ?> assigned successfully.</p>
  <p><a href="select_incident.php">Select Another Incident</a></p>
<?php else: ?>
  <p class="error">Could not assign incident.</p>
<?php endif; ?>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
