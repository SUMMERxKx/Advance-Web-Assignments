<?php
/*
 * Add Meal Plan Page
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Allows users to assign a recipe to a specific date and meal type
 */

session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../includes/database.php';

$page_title = 'Plan a Meal';
$user_id = $_SESSION['user_id'];
$errors = [];
$success = false;

// Get user's recipes for dropdown
$recipes = db_query(
    "SELECT id, title, prep_time FROM recipes WHERE user_id = ? ORDER BY title",
    [$user_id]
);

// Pre-fill date if provided
$prefill_date = $_GET['date'] ?? date('Y-m-d');

// Form data for repopulating
$form_data = [
    'recipe_id' => '',
    'meal_date' => $prefill_date,
    'meal_type' => 'dinner'
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_id = intval($_POST['recipe_id'] ?? 0);
    $meal_date = trim($_POST['meal_date'] ?? '');
    $meal_type = trim($_POST['meal_type'] ?? '');

    // Store for repopulating
    $form_data = compact('recipe_id', 'meal_date', 'meal_type');

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

    // Insert meal plan if no errors
    if (empty($errors)) {
        db_execute(
            "INSERT INTO meal_plans (user_id, recipe_id, meal_date, meal_type) VALUES (?, ?, ?, ?)",
            [$user_id, $recipe_id, $meal_date, $meal_type]
        );

        $success = true;
        // Reset form
        $form_data = [
            'recipe_id' => '',
            'meal_date' => date('Y-m-d'),
            'meal_type' => 'dinner'
        ];
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1>Plan a Meal</h1>
        <p>Assign a recipe to a specific date</p>
    </div>
    <a href="planner.php" class="btn btn-secondary">← Back to Planner</a>
</div>

<?php if ($success): ?>
    <div class="message message-success">
        Meal planned successfully!
        <a href="planner.php">View your meal plan</a> or add another below.
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

<?php if (empty($recipes)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">📝</div>
        <h3>No Recipes Yet</h3>
        <p>You need to add some recipes before you can plan meals.</p>
        <a href="../recipes/add.php" class="btn btn-primary">Add a Recipe</a>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="recipe_id" class="form-label">Recipe *</label>
                    <select id="recipe_id" name="recipe_id" class="form-select" required>
                        <option value="">Select a recipe...</option>
                        <?php foreach ($recipes as $recipe): ?>
                            <option value="<?= $recipe['id'] ?>" <?= $form_data['recipe_id'] == $recipe['id'] ? 'selected' : '' ?>>
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
                            value="<?= htmlspecialchars($form_data['meal_date']) ?>" required />
                    </div>

                    <div class="form-group form-group-half">
                        <label for="meal_type" class="form-label">Meal Type *</label>
                        <select id="meal_type" name="meal_type" class="form-select" required>
                            <option value="breakfast" <?= $form_data['meal_type'] === 'breakfast' ? 'selected' : '' ?>>🌅
                                Breakfast</option>
                            <option value="lunch" <?= $form_data['meal_type'] === 'lunch' ? 'selected' : '' ?>>☀️ Lunch
                            </option>
                            <option value="dinner" <?= $form_data['meal_type'] === 'dinner' ? 'selected' : '' ?>>🌙 Dinner
                            </option>
                            <option value="snack" <?= $form_data['meal_type'] === 'snack' ? 'selected' : '' ?>>🍎 Snack
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">Plan This Meal</button>
                    <a href="planner.php" class="btn btn-secondary btn-lg">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

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
    }

    @media (max-width: 500px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>