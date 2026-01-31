<?php
/*
 * Edit Meal Plan Page
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Allows users to update an existing meal plan
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
$errors = [];
$success = false;

// Verify meal plan exists and belongs to user
$meal = db_query_one(
    "SELECT mp.*, r.title as recipe_title 
   FROM meal_plans mp 
   JOIN recipes r ON mp.recipe_id = r.id 
   WHERE mp.id = ? AND mp.user_id = ?",
    [$meal_id, $user_id]
);

if (!$meal) {
    header('Location: planner.php');
    exit;
}

$page_title = 'Edit Meal Plan';

// Get user's recipes for dropdown
$recipes = db_query(
    "SELECT id, title, prep_time FROM recipes WHERE user_id = ? ORDER BY title",
    [$user_id]
);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_id = intval($_POST['recipe_id'] ?? 0);
    $meal_date = trim($_POST['meal_date'] ?? '');
    $meal_type = trim($_POST['meal_type'] ?? '');

    // Validation
    if ($recipe_id <= 0) {
        $errors[] = 'Please select a recipe.';
    } else {
        // Verify recipe belongs to user
        $recipe_check = db_query_one(
            "SELECT id FROM recipes WHERE id = ? AND user_id = ?",
            [$recipe_id, $user_id]
        );
        if (!$recipe_check) {
            $errors[] = 'Invalid recipe selected.';
        }
    }

    if ($meal_date === '' || !strtotime($meal_date)) {
        $errors[] = 'Please select a valid date.';
    }

    $valid_types = ['breakfast', 'lunch', 'dinner', 'snack'];
    if (!in_array($meal_type, $valid_types)) {
        $errors[] = 'Please select a valid meal type.';
    }

    // Update meal plan if no errors
    if (empty($errors)) {
        db_execute(
            "UPDATE meal_plans SET recipe_id = ?, meal_date = ?, meal_type = ? WHERE id = ? AND user_id = ?",
            [$recipe_id, $meal_date, $meal_type, $meal_id, $user_id]
        );

        $success = true;

        // Refresh meal data
        $meal = db_query_one(
            "SELECT mp.*, r.title as recipe_title 
       FROM meal_plans mp 
       JOIN recipes r ON mp.recipe_id = r.id 
       WHERE mp.id = ? AND mp.user_id = ?",
            [$meal_id, $user_id]
        );
    } else {
        // Use submitted data if there are errors
        $meal['recipe_id'] = $recipe_id;
        $meal['meal_date'] = $meal_date;
        $meal['meal_type'] = $meal_type;
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1>Edit Meal Plan</h1>
        <p>Update your planned meal</p>
    </div>
    <a href="planner.php" class="btn btn-secondary">← Back to Planner</a>
</div>

<?php if ($success): ?>
    <div class="message message-success">
        Meal plan updated successfully! <a href="planner.php">Back to planner</a>
    </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="message message-error">
        <?php foreach ($errors as $error): ?>
            <div>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="">
            <div class="form-group">
                <label for="recipe_id" class="form-label">Recipe *</label>
                <select id="recipe_id" name="recipe_id" class="form-select" required>
                    <option value="">Select a recipe...</option>
                    <?php foreach ($recipes as $recipe): ?>
                        <option value="<?= $recipe['id'] ?>" <?= $meal['recipe_id'] == $recipe['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($recipe['title']) ?> (
                            <?= $recipe['prep_time'] ?> min)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group form-group-half">
                    <label for="meal_date" class="form-label">Date *</label>
                    <input type="date" id="meal_date" name="meal_date" class="form-input"
                        value="<?= htmlspecialchars($meal['meal_date']) ?>" required />
                </div>

                <div class="form-group form-group-half">
                    <label for="meal_type" class="form-label">Meal Type *</label>
                    <select id="meal_type" name="meal_type" class="form-select" required>
                        <option value="breakfast" <?= $meal['meal_type'] === 'breakfast' ? 'selected' : '' ?>>🌅 Breakfast
                        </option>
                        <option value="lunch" <?= $meal['meal_type'] === 'lunch' ? 'selected' : '' ?>>☀️ Lunch</option>
                        <option value="dinner" <?= $meal['meal_type'] === 'dinner' ? 'selected' : '' ?>>🌙 Dinner</option>
                        <option value="snack" <?= $meal['meal_type'] === 'snack' ? 'selected' : '' ?>>🍎 Snack</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">Update Meal Plan</button>
                <a href="planner.php" class="btn btn-secondary btn-lg">Cancel</a>
                <div class="toolbar-spacer"></div>
                <a href="delete.php?id=<?= $meal_id ?>" class="btn btn-danger btn-lg">Delete</a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-row {
        display: flex;
        gap: var(--space-lg);
    }

    .form-group-half {
        flex: 1;
    }

    .form-actions {
        display: flex;
        gap: var(--space-md);
        margin-top: var(--space-lg);
        padding-top: var(--space-lg);
        border-top: 1px solid var(--color-border-light);
        flex-wrap: wrap;
    }

    @media (max-width: 500px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>