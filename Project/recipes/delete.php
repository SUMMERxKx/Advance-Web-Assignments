<?php
/*
 * Delete Recipe Page
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Handles recipe deletion with confirmation
 */

session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../includes/database.php';

$user_id = $_SESSION['user_id'];
$recipe_id = intval($_GET['id'] ?? 0);

// Verify recipe exists and belongs to user
$recipe = db_query_one(
    "SELECT id, title FROM recipes WHERE id = ? AND user_id = ?",
    [$recipe_id, $user_id]
);

if (!$recipe) {
    header('Location: list.php');
    exit;
}

$page_title = 'Delete Recipe';
$error = '';

// Process deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirm = $_POST['confirm'] ?? '';

    if ($confirm === 'yes') {
        // Delete associated meal plans first (cascade should handle this, but explicit is safer)
        db_execute("DELETE FROM meal_plans WHERE recipe_id = ? AND user_id = ?", [$recipe_id, $user_id]);

        // Delete the recipe
        db_execute("DELETE FROM recipes WHERE id = ? AND user_id = ?", [$recipe_id, $user_id]);

        // Redirect to list with success
        header('Location: list.php?deleted=1');
        exit;
    } else {
        // User cancelled, go back to list
        header('Location: list.php');
        exit;
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="delete-container">
    <div class="delete-card">
        <div class="delete-icon">⚠️</div>
        <h2>Delete Recipe?</h2>
        <p class="delete-message">
            Are you sure you want to delete <strong>"
                <?= htmlspecialchars($recipe['title']) ?>"
            </strong>?
        </p>
        <p class="delete-warning">
            This will also remove this recipe from any meal plans. This action cannot be undone.
        </p>

        <form method="POST" action="">
            <div class="delete-actions">
                <a href="list.php" class="btn btn-secondary btn-lg">Cancel</a>
                <button type="submit" name="confirm" value="yes" class="btn btn-danger btn-lg">
                    Yes, Delete Recipe
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .delete-container {
        display: flex;
        justify-content: center;
        padding: var(--space-2xl) 0;
    }

    .delete-card {
        background: var(--color-white);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        max-width: 480px;
        width: 100%;
        padding: var(--space-2xl);
        text-align: center;
    }

    .delete-icon {
        font-size: 64px;
        margin-bottom: var(--space-lg);
    }

    .delete-card h2 {
        margin-bottom: var(--space-md);
    }

    .delete-message {
        font-size: var(--font-size-base);
        color: var(--color-text);
        margin-bottom: var(--space-sm);
    }

    .delete-warning {
        font-size: var(--font-size-sm);
        color: var(--color-danger);
        margin-bottom: var(--space-xl);
    }

    .delete-actions {
        display: flex;
        gap: var(--space-md);
        justify-content: center;
    }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>