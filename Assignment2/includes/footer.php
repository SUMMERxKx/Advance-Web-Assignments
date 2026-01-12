<?php 
// Footer template - Student: Samar Khajuria, ID: T00714740
?>
<!-- Custom confirmation dialog -->
<div id="confirm-overlay" class="confirm-overlay">
  <div class="confirm-dialog">
    <h3>Confirm Action</h3>
    <p id="confirm-message">Are you sure you want to proceed?</p>
    <div class="confirm-buttons">
      <button type="button" class="confirm-yes" id="confirm-yes">Yes</button>
      <button type="button" class="confirm-no" id="confirm-no">Cancel</button>
    </div>
  </div>
</div>

<script>
// Custom confirmation dialog
let confirmCallback = null;

document.getElementById('confirm-yes').addEventListener('click', function() {
  if (confirmCallback) {
    confirmCallback();
  }
  document.getElementById('confirm-overlay').style.display = 'none';
  confirmCallback = null;
});

document.getElementById('confirm-no').addEventListener('click', function() {
  document.getElementById('confirm-overlay').style.display = 'none';
  confirmCallback = null;
});

function showConfirm(message, callback) {
  document.getElementById('confirm-message').textContent = message;
  document.getElementById('confirm-overlay').style.display = 'flex';
  confirmCallback = callback;
}
</script>
</main>
<footer>
  <p>&copy; SportsPro</p>
</footer>
</body>
</html>
