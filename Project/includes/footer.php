<?php
/*
 * Footer Template
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Contains custom confirmation dialog and footer content
 */
?>
</main>

<!-- Custom Confirmation Dialog -->
<div id="confirm-overlay" class="confirm-overlay">
    <div class="confirm-dialog">
        <div class="confirm-icon">⚠️</div>
        <h3 class="confirm-title">Confirm Action</h3>
        <p id="confirm-message" class="confirm-message">Are you sure you want to proceed?</p>
        <div class="confirm-buttons">
            <button type="button" class="btn btn-secondary" id="confirm-no">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirm-yes">Confirm</button>
        </div>
    </div>
</div>

<script>
    // Custom confirmation dialog functionality
    let confirmCallback = null;

    document.getElementById('confirm-yes').addEventListener('click', function () {
        if (confirmCallback) {
            confirmCallback();
        }
        document.getElementById('confirm-overlay').style.display = 'none';
        confirmCallback = null;
    });

    document.getElementById('confirm-no').addEventListener('click', function () {
        document.getElementById('confirm-overlay').style.display = 'none';
        confirmCallback = null;
    });

    // Close on overlay click
    document.getElementById('confirm-overlay').addEventListener('click', function (e) {
        if (e.target === this) {
            this.style.display = 'none';
            confirmCallback = null;
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.getElementById('confirm-overlay').style.display = 'none';
            confirmCallback = null;
        }
    });

    /**
     * Shows a custom confirmation dialog
     * @param {string} message - The message to display
     * @param {function} callback - Function to call if user confirms
     */
    function showConfirm(message, callback) {
        document.getElementById('confirm-message').textContent = message;
        document.getElementById('confirm-overlay').style.display = 'flex';
        confirmCallback = callback;
    }
</script>

<footer class="site-footer">
    <div class="footer-content">
        <p>&copy;
            <?= date('Y') ?> Recipe Planner. Built for COMP 3541.
        </p>
    </div>
</footer>
</body>

</html>