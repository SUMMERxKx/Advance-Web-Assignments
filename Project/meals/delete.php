<?php
/*
 * Delete Meal Plan Page
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Handles meal plan deletion with confirmation
 */

session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../includes/database.php';

$user_id = $_SESSION['user_id'];
$meal_id = intval($_GET['id'] ?? 0);

// Verify meal plan exists and belongs to user
$meal = db_query_one(
    "SELECT mp.id, mp.meal_date, mp.meal_type, r.title as recipe_title 
   FROM meal_plans mp 
   JOIN recipes r ON mp.recipe_id = r.id 
   WHERE mp.id = ? AND mp.user_id = ?",
    [$meal_id, $user_id]
);

if (!$meal) {
    header('Location: planner.php');
    exit;
}

$page_title = 'Delete Meal Plan';

// Process deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirm = $_POST['confirm'] ?? '';

    if ($confirm === 'yes') {
        db_execute("DELETE FROM meal_plans WHERE id = ? AND user_id = ?", [$meal_id, $user_id]);

        // Redirect to planner
        header('Location: planner.php?deleted=1');
        exit;
    } else {
        header('Location: planner.php');
        exit;
    }
}

// Format date for display
$formatted_date = date('l, F j, Y', strtotime($meal['meal_date']));

include __DIR__ . '/../includes/header.php';
?>

<div class="delete-container">
    <div class="delete-card">
        <div class="delete-icon">⚠️</div>
        <h2>Remove Planned Meal?</h2>
        <p class="delete-message">
            Are you sure you want to remove this meal from your plan?
        </p>

        <div class="meal-preview">
            <div class="meal-preview-header">
                <span class="badge badge-<?= $meal['meal_type'] ?>">
                    <?= $meal['meal_type'] ?>
                </span>
                <span class="meal-preview-date">
                    <?= $formatted_date ?>
                </span>
            </div>
            <div class="meal-preview-title">
                <?= htmlspecialchars($meal['recipe_title']) ?>
            </div>
        </div>

        <form method="POST" action="">
            <div class="delete-actions">
                <a href="planner.php" class="btn btn-secondary btn-lg">Cancel</a>
                <button type="submit" name="confirm" value="yes" class="btn btn-danger btn-lg">
                    Yes, Remove It
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
        color: var(--color-text-secondary);
        margin-bottom: var(--space-lg);
    }

    .meal-preview {
        background: var(--color-bg-secondary);
        border-radius: var(--radius-md);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        text-align: left;
    }

    .meal-preview-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-sm);
    }

    .meal-preview-date {
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
    }

    .meal-preview-title {
        font-size: var(--font-size-lg);
        font-weight: 600;
        color: var(--color-text);
    }

    .delete-actions {
        display: flex;
        gap: var(--space-md);
        justify-content: center;
    }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>