<?php
/*
 * Recipe List Page
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Displays all user recipes with search and sort functionality
 */

session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../includes/database.php';

$page_title = 'My Recipes';
$user_id = $_SESSION['user_id'];

// Get search and sort parameters
$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'newest';

// Build query based on parameters
$sql = "SELECT id, title, description, prep_time, created_at FROM recipes WHERE user_id = ?";
$params = [$user_id];

// Add search filter
if ($search !== '') {
    $sql .= " AND (title LIKE ? OR description LIKE ? OR ingredients LIKE ?)";
    $search_term = '%' . $search . '%';
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

// Add sorting
switch ($sort) {
    case 'oldest':
        $sql .= " ORDER BY created_at ASC";
        break;
    case 'prep_asc':
        $sql .= " ORDER BY prep_time ASC";
        break;
    case 'prep_desc':
        $sql .= " ORDER BY prep_time DESC";
        break;
    case 'alpha':
        $sql .= " ORDER BY title ASC";
        break;
    case 'newest':
    default:
        $sql .= " ORDER BY created_at DESC";
        break;
}

$recipes = db_query($sql, $params);
$recipe_count = count($recipes);

include __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1>My Recipes</h1>
        <p>
            <?= $recipe_count ?> recipe
            <?= $recipe_count !== 1 ? 's' : '' ?> total
        </p>
    </div>
    <a href="add.php" class="btn btn-primary">+ Add Recipe</a>
</div>

<!-- Search and Filter Toolbar -->
<div class="toolbar">
    <form method="GET" action="" class="toolbar-form">
        <input type="text" name="search" class="form-input" placeholder="Search recipes..."
            value="<?= htmlspecialchars($search) ?>" />
        <select name="sort" class="form-select" onchange="this.form.submit()">
            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
            <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
            <option value="alpha" <?= $sort === 'alpha' ? 'selected' : '' ?>>A-Z</option>
            <option value="prep_asc" <?= $sort === 'prep_asc' ? 'selected' : '' ?>>Prep Time (Low to High)</option>
            <option value="prep_desc" <?= $sort === 'prep_desc' ? 'selected' : '' ?>>Prep Time (High to Low)</option>
        </select>
        <button type="submit" class="btn btn-secondary">Search</button>
        <?php if ($search !== '' || $sort !== 'newest'): ?>
            <a href="list.php" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Recipe List -->
<?php if (empty($recipes)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">📝</div>
        <h3>No recipes found</h3>
        <?php if ($search !== ''): ?>
            <p>No recipes match your search. Try different keywords.</p>
            <a href="list.php" class="btn btn-secondary">Clear Search</a>
        <?php else: ?>
            <p>Start building your recipe collection!</p>
            <a href="add.php" class="btn btn-primary">Add Your First Recipe</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="recipe-list">
        <?php foreach ($recipes as $recipe): ?>
            <div class="recipe-item">
                <div class="recipe-info">
                    <h4>
                        <?= htmlspecialchars($recipe['title']) ?>
                    </h4>
                    <div class="recipe-meta">
                        <span>⏱️
                            <?= $recipe['prep_time'] ?> min
                        </span>
                        <span>•</span>
                        <span>Added
                            <?= date('M j, Y', strtotime($recipe['created_at'])) ?>
                        </span>
                    </div>
                    <?php if (!empty($recipe['description'])): ?>
                        <p class="recipe-description">
                            <?= htmlspecialchars(substr($recipe['description'], 0, 100)) ?>
                            <?= strlen($recipe['description']) > 100 ? '...' : '' ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="table-actions">
                    <a href="edit.php?id=<?= $recipe['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                    <a href="delete.php?id=<?= $recipe['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: var(--space-xl);
    }

    .page-header h1 {
        margin-bottom: var(--space-xs);
    }

    .page-header p {
        margin: 0;
    }

    .toolbar-form {
        display: flex;
        gap: var(--space-md);
        flex-wrap: wrap;
        width: 100%;
    }

    .toolbar-form .form-input {
        flex: 1;
        min-width: 200px;
    }

    .toolbar-form .form-select {
        width: auto;
        min-width: 180px;
    }

    .recipe-description {
        font-size: var(--font-size-sm);
        color: var(--color-text-tertiary);
        margin: var(--space-sm) 0 0 0;
    }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>