<?php
/*
 * Add Recipe Page
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Allows users to create a new recipe
 */

session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../includes/database.php';

$page_title = 'Add Recipe';
$user_id = $_SESSION['user_id'];
$errors = [];
$success = false;

// Form data for repopulating
$form_data = [
    'title' => '',
    'description' => '',
    'ingredients' => '',
    'instructions' => '',
    'prep_time' => ''
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $instructions = trim($_POST['instructions'] ?? '');
    $prep_time = intval($_POST['prep_time'] ?? 0);

    // Store for repopulating
    $form_data = compact('title', 'description', 'ingredients', 'instructions', 'prep_time');

    // Validation
    if ($title === '') {
        $errors[] = 'Recipe title is required.';
    } elseif (strlen($title) > 255) {
        $errors[] = 'Title must be less than 255 characters.';
    }

    if ($ingredients === '') {
        $errors[] = 'Ingredients are required.';
    }

    if ($instructions === '') {
        $errors[] = 'Instructions are required.';
    }

    if ($prep_time < 0) {
        $errors[] = 'Prep time cannot be negative.';
    }

    // Insert recipe if no errors
    if (empty($errors)) {
        db_execute(
            "INSERT INTO recipes (user_id, title, description, ingredients, instructions, prep_time) 
       VALUES (?, ?, ?, ?, ?, ?)",
            [$user_id, $title, $description, $ingredients, $instructions, $prep_time]
        );

        $success = true;
        // Clear form
        $form_data = [
            'title' => '',
            'description' => '',
            'ingredients' => '',
            'instructions' => '',
            'prep_time' => ''
        ];
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1>Add New Recipe</h1>
        <p>Create a new recipe for your collection</p>
    </div>
    <a href="list.php" class="btn btn-secondary">← Back to Recipes</a>
</div>

<?php if ($success): ?>
    <div class="message message-success">
        Recipe added successfully!
        <a href="list.php">View all recipes</a> or add another below.
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
                <label for="title" class="form-label">Recipe Title *</label>
                <input type="text" id="title" name="title" class="form-input" placeholder="e.g., Classic Pancakes"
                    value="<?= htmlspecialchars($form_data['title']) ?>" required />
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-textarea"
                    placeholder="A short description of this recipe..."
                    rows="2"><?= htmlspecialchars($form_data['description']) ?></textarea>
                <div class="form-hint">Optional. A brief overview of the dish.</div>
            </div>

            <div class="form-group">
                <label for="ingredients" class="form-label">Ingredients *</label>
                <textarea id="ingredients" name="ingredients" class="form-textarea"
                    placeholder="List each ingredient on a new line..." rows="6"
                    required><?= htmlspecialchars($form_data['ingredients']) ?></textarea>
                <div class="form-hint">Enter each ingredient on a separate line.</div>
            </div>

            <div class="form-group">
                <label for="instructions" class="form-label">Instructions *</label>
                <textarea id="instructions" name="instructions" class="form-textarea"
                    placeholder="Step-by-step instructions..." rows="8"
                    required><?= htmlspecialchars($form_data['instructions']) ?></textarea>
                <div class="form-hint">Provide clear, step-by-step directions.</div>
            </div>

            <div class="form-group">
                <label for="prep_time" class="form-label">Prep Time (minutes)</label>
                <input type="number" id="prep_time" name="prep_time" class="form-input" placeholder="e.g., 30"
                    value="<?= htmlspecialchars($form_data['prep_time']) ?>" min="0" style="max-width: 150px;" />
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">Save Recipe</button>
                <a href="list.php" class="btn btn-secondary btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-actions {
        display: flex;
        gap: var(--space-md);
        margin-top: var(--space-lg);
        padding-top: var(--space-lg);
        border-top: 1px solid var(--color-border-light);
    }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>