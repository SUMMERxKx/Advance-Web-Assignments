<?php
// Update incident page
// Technicians can update description and close incidents
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/database.php';

require_role('tech');

$techID = $_SESSION['tech']['techID'];
$incidentID = (int)($_POST['incidentID'] ?? $_GET['incidentID'] ?? 0);

if (!$incidentID) { 
  redirect_to('technician/select_incident.php'); 
  exit; 
}

// Get incident details (only if assigned to this technician)
$incident = db_one(
  "SELECT i.*, p.name AS productName
   FROM incidents i
   JOIN products p ON i.productCode = p.productCode
   WHERE i.incidentID = ? AND i.techID = ?", [$incidentID, $techID]
);

if (!$incident) { 
  redirect_to('technician/select_incident.php'); 
  exit; 
}

$successMsg = null;

// Handle update form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
  $description = trim($_POST['description'] ?? '');
  $dateClosed = trim($_POST['dateClosed'] ?? '');
  
  // Build update query
  $params = [$description, $incidentID, $techID];
  $sql = "UPDATE incidents SET description = ?" . ($dateClosed ? ", dateClosed = ?" : "") . " WHERE incidentID = ? AND techID = ?";
  
  if ($dateClosed) {
    $params = [$description, $dateClosed, $incidentID, $techID];
  }
  
  $updated = db_exec($sql, $params) > 0;
  $successMsg = $updated ? "Incident updated." : "No changes.";
  
  // Reload incident data
  $incident = db_one(
    "SELECT i.*, p.name AS productName
     FROM incidents i
     JOIN products p ON i.productCode = p.productCode
     WHERE i.incidentID = ? AND i.techID = ?", [$incidentID, $techID]
  );
}

require __DIR__ . '/../includes/header.php';
?>

<h2>Update Incident #<?= (int)$incident['incidentID'] ?></h2>

<p><a href="<?= htmlspecialchars(app_url('technician/select_incident.php')) ?>">Select Another Incident</a> | <a href="<?= htmlspecialchars(app_url('technician/logout.php')) ?>">Logout</a></p>

<?php if ($successMsg): ?><p class="success"><?= htmlspecialchars($successMsg) ?></p><?php endif; ?>

<form method="post">
  <input type="hidden" name="incidentID" value="<?= (int)$incident['incidentID'] ?>">
  
  <p><strong>Product:</strong> <?= htmlspecialchars($incident['productName']) ?></p>
  <p><strong>Opened:</strong> <?= htmlspecialchars(date('n-j-Y', strtotime($incident['dateOpened']))) ?></p>
  
  <label>Description:<br>
    <textarea name="description" rows="5" cols="60"><?= htmlspecialchars($incident['description']) ?></textarea>
  </label><br>
  
  <label>Date Closed (YYYY-MM-DD): <input type="date" name="dateClosed" value="<?= htmlspecialchars($incident['dateClosed'] ?? '') ?>"></label><br>
  
  <button type="submit" name="update" value="1">Update Incident</button>
</form>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>
