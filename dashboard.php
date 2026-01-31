<?php
/*
 * User Dashboard
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Protected page showing user's overview and quick stats
 */

session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

require_once __DIR__ . '/includes/database.php';

$page_title = 'Dashboard';
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch stats
$recipe_count = db_query_one(
    "SELECT COUNT(*) as count FROM recipes WHERE user_id = ?",
    [$user_id]
)['count'];

// Upcoming meals (next 7 days)
$upcoming_meals = db_query(
    "SELECT mp.id, mp.meal_date, mp.meal_type, r.title as recipe_title
   FROM meal_plans mp
   JOIN recipes r ON mp.recipe_id = r.id
   WHERE mp.user_id = ? AND mp.meal_date >= CURDATE() AND mp.meal_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
   ORDER BY mp.meal_date, FIELD(mp.meal_type, 'breakfast', 'lunch', 'dinner', 'snack')
   LIMIT 10",
    [$user_id]
);

$upcoming_count = count($upcoming_meals);

// Recent recipes
$recent_recipes = db_query(
    "SELECT id, title, prep_time, created_at FROM recipes WHERE user_id = ? ORDER BY created_at DESC LIMIT 5",
    [$user_id]
);

include __DIR__ . '/includes/header.php';
?>

<div class="dashboard-header">
    <div>
        <h1>Welcome back,
            <?= htmlspecialchars($user_name) ?>!
        </h1>
        <p>Here's an overview of your recipes and meal plans.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">
            <?= $recipe_count ?>
        </div>
        <div class="stat-label">Total Recipes</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">
            <?= $upcoming_count ?>
        </div>
        <div class="stat-label">Meals This Week</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="recipes/add.php" class="btn btn-primary">+ Add Recipe</a>
    <a href="meals/add.php" class="btn btn-secondary">+ Plan a Meal</a>
</div>

<!-- Two Column Layout -->
<div class="dashboard-grid">
    <!-- Upcoming Meals -->
    <div class="card">
        <div class="card-header flex flex-between">
            <h3>Upcoming Meals</h3>
            <a href="meals/planner.php" class="btn btn-sm btn-secondary">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($upcoming_meals)): ?>
                <div class="empty-state">
                    <p>No meals planned for this week.</p>
                    <a href="meals/add.php" class="btn btn-primary btn-sm">Plan a Meal</a>
                </div>
            <?php else: ?>
                <div class="meal-list">
                    <?php
                    $current_date = null;
                    foreach ($upcoming_meals as $meal):
                        $meal_date = $meal['meal_date'];
                        $is_today = $meal_date === date('Y-m-d');
                        $is_tomorrow = $meal_date === date('Y-m-d', strtotime('+1 day'));

                        if ($current_date !== $meal_date):
                            $current_date = $meal_date;
                            $date_label = $is_today ? 'Today' : ($is_tomorrow ? 'Tomorrow' : date('l, M j', strtotime($meal_date)));
                            ?>
                            <div class="meal-date-header">
                                <?= $date_label ?>
                            </div>
                        <?php endif; ?>

                        <div class="meal-item">
                            <span class="badge badge-<?= $meal['meal_type'] ?>">
                                <?= $meal['meal_type'] ?>
                            </span>
                            <span class="meal-title">
                                <?= htmlspecialchars($meal['recipe_title']) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Recipes -->
    <div class="card">
        <div class="card-header flex flex-between">
            <h3>Recent Recipes</h3>
            <a href="recipes/list.php" class="btn btn-sm btn-secondary">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recent_recipes)): ?>
                <div class="empty-state">
                    <p>No recipes yet.</p>
                    <a href="recipes/add.php" class="btn btn-primary btn-sm">Add Recipe</a>
                </div>
            <?php else: ?>
                <div class="recipe-list-simple">
                    <?php foreach ($recent_recipes as $recipe): ?>
                        <div class="recipe-list-item">
                            <a href="recipes/edit.php?id=<?= $recipe['id'] ?>" class="recipe-name">
                                <?= htmlspecialchars($recipe['title']) ?>
                            </a>
                            <span class="recipe-meta">
                                <?= $recipe['prep_time'] ?> min
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Dashboard specific styles */
    .dashboard-header {
        margin-bottom: var(--space-xl);
    }

    .dashboard-header h1 {
        margin-bottom: var(--space-xs);
    }

    .dashboard-header p {
        margin: 0;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--space-lg);
    }

    .meal-list {
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }

    .meal-date-header {
        font-size: var(--font-size-xs);
        font-weight: 600;
        color: var(--color-text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: var(--space-md);
        margin-bottom: var(--space-xs);
    }

    .meal-date-header:first-child {
        margin-top: 0;
    }

    .meal-item {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-sm) 0;
    }

    .meal-title {
        font-size: var(--font-size-sm);
        color: var(--color-text);
    }

    .recipe-list-simple {
        display: flex;
        flex-direction: column;
    }

    .recipe-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--space-md) 0;
        border-bottom: 1px solid var(--color-border-light);
    }

    .recipe-list-item:last-child {
        border-bottom: none;
    }

    .recipe-name {
        font-weight: 500;
        color: var(--color-text);
    }

    .recipe-name:hover {
        color: var(--color-primary);
    }

    .empty-state {
        text-align: center;
        padding: var(--space-lg);
    }

    .empty-state p {
        margin-bottom: var(--space-md);
    }
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>