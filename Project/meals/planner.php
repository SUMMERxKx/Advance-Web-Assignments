<?php
/*
 * Meal Planner Page
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Displays planned meals organized by date
 */

session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../includes/database.php';

$page_title = 'Meal Planner';
$user_id = $_SESSION['user_id'];

// Get date range filter (default: this week and next week)
$start_date = $_GET['start'] ?? date('Y-m-d');
$end_date = $_GET['end'] ?? date('Y-m-d', strtotime('+13 days'));

// Validate dates
if (!strtotime($start_date))
    $start_date = date('Y-m-d');
if (!strtotime($end_date))
    $end_date = date('Y-m-d', strtotime('+13 days'));

// Fetch meals for date range
$meals = db_query(
    "SELECT mp.id, mp.meal_date, mp.meal_type, mp.created_at,
          r.id as recipe_id, r.title as recipe_title, r.prep_time
   FROM meal_plans mp
   JOIN recipes r ON mp.recipe_id = r.id
   WHERE mp.user_id = ? AND mp.meal_date >= ? AND mp.meal_date <= ?
   ORDER BY mp.meal_date ASC, FIELD(mp.meal_type, 'breakfast', 'lunch', 'dinner', 'snack')",
    [$user_id, $start_date, $end_date]
);

// Group meals by date
$meals_by_date = [];
foreach ($meals as $meal) {
    $date = $meal['meal_date'];
    if (!isset($meals_by_date[$date])) {
        $meals_by_date[$date] = [];
    }
    $meals_by_date[$date][] = $meal;
}

// Generate date range for display
$date_range = [];
$current = strtotime($start_date);
$end = strtotime($end_date);
while ($current <= $end) {
    $date_range[] = date('Y-m-d', $current);
    $current = strtotime('+1 day', $current);
}

include __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1>Meal Planner</h1>
        <p>Plan your meals for the week</p>
    </div>
    <a href="add.php" class="btn btn-primary">+ Plan a Meal</a>
</div>

<!-- Date Range Filter -->
<div class="toolbar">
    <form method="GET" action="" class="toolbar-form">
        <div class="date-range-group">
            <label class="form-label-inline">From</label>
            <input type="date" name="start" class="form-input" value="<?= htmlspecialchars($start_date) ?>" />
            <label class="form-label-inline">To</label>
            <input type="date" name="end" class="form-input" value="<?= htmlspecialchars($end_date) ?>" />
            <button type="submit" class="btn btn-secondary">Update</button>
        </div>
    </form>
    <div class="toolbar-spacer"></div>
    <div class="quick-nav">
        <a href="?start=<?= date('Y-m-d') ?>&end=<?= date('Y-m-d', strtotime('+6 days')) ?>"
            class="btn btn-sm btn-secondary">This Week</a>
        <a href="?start=<?= date('Y-m-d', strtotime('next monday')) ?>&end=<?= date('Y-m-d', strtotime('next monday +6 days')) ?>"
            class="btn btn-sm btn-secondary">Next Week</a>
    </div>
</div>

<!-- Meal Calendar Grid -->
<div class="meal-calendar">
    <?php foreach ($date_range as $date):
        $is_today = $date === date('Y-m-d');
        $is_past = $date < date('Y-m-d');
        $day_meals = $meals_by_date[$date] ?? [];
        $day_name = date('l', strtotime($date));
        $day_date = date('M j', strtotime($date));
        ?>
        <div class="calendar-day <?= $is_today ? 'is-today' : '' ?> <?= $is_past ? 'is-past' : '' ?>">
            <div class="day-header">
                <span class="day-name">
                    <?= $day_name ?>
                </span>
                <span class="day-date">
                    <?= $day_date ?>
                    <?= $is_today ? ' (Today)' : '' ?>
                </span>
            </div>
            <div class="day-meals">
                <?php if (empty($day_meals)): ?>
                    <div class="no-meals">
                        <a href="add.php?date=<?= $date ?>" class="add-meal-link">+ Add meal</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($day_meals as $meal): ?>
                        <div class="meal-card">
                            <div class="meal-card-header">
                                <span class="badge badge-<?= $meal['meal_type'] ?>">
                                    <?= $meal['meal_type'] ?>
                                </span>
                                <div class="meal-card-actions">
                                    <a href="edit.php?id=<?= $meal['id'] ?>" class="action-link" title="Edit">✏️</a>
                                    <a href="delete.php?id=<?= $meal['id'] ?>" class="action-link" title="Delete">🗑️</a>
                                </div>
                            </div>
                            <div class="meal-card-title">
                                <?= htmlspecialchars($meal['recipe_title']) ?>
                            </div>
                            <div class="meal-card-meta">⏱️
                                <?= $meal['prep_time'] ?> min
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <a href="add.php?date=<?= $date ?>" class="add-meal-link small">+ Add another</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .toolbar-form {
        display: flex;
        gap: var(--space-md);
        flex-wrap: wrap;
        align-items: center;
    }

    .date-range-group {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }

    .date-range-group .form-input {
        width: auto;
    }

    .form-label-inline {
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
        white-space: nowrap;
    }

    .quick-nav {
        display: flex;
        gap: var(--space-sm);
    }

    /* Meal Calendar */
    .meal-calendar {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: var(--space-md);
    }

    .calendar-day {
        background: var(--color-white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border-light);
        overflow: hidden;
        min-height: 180px;
    }

    .calendar-day.is-today {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 1px var(--color-primary);
    }

    .calendar-day.is-past {
        opacity: 0.7;
    }

    .day-header {
        padding: var(--space-md);
        background: var(--color-bg-secondary);
        border-bottom: 1px solid var(--color-border-light);
    }

    .day-name {
        font-weight: 600;
        font-size: var(--font-size-sm);
        display: block;
        color: var(--color-text);
    }

    .day-date {
        font-size: var(--font-size-xs);
        color: var(--color-text-secondary);
    }

    .is-today .day-header {
        background: var(--color-primary);
    }

    .is-today .day-name,
    .is-today .day-date {
        color: var(--color-white);
    }

    .day-meals {
        padding: var(--space-sm);
        display: flex;
        flex-direction: column;
        gap: var(--space-sm);
    }

    .no-meals {
        padding: var(--space-lg);
        text-align: center;
    }

    .add-meal-link {
        font-size: var(--font-size-sm);
        color: var(--color-text-tertiary);
        text-decoration: none;
        display: block;
        padding: var(--space-sm);
        border-radius: var(--radius-sm);
        transition: all 0.2s ease;
    }

    .add-meal-link:hover {
        background: var(--color-bg);
        color: var(--color-primary);
        opacity: 1;
    }

    .add-meal-link.small {
        font-size: var(--font-size-xs);
        padding: var(--space-xs);
    }

    /* Meal Cards */
    .meal-card {
        background: var(--color-bg-secondary);
        border-radius: var(--radius-md);
        padding: var(--space-sm);
    }

    .meal-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-xs);
    }

    .meal-card-actions {
        display: flex;
        gap: 4px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .meal-card:hover .meal-card-actions {
        opacity: 1;
    }

    .action-link {
        font-size: 12px;
        text-decoration: none;
        padding: 2px;
    }

    .meal-card-title {
        font-size: var(--font-size-sm);
        font-weight: 500;
        color: var(--color-text);
        margin-bottom: 2px;
    }

    .meal-card-meta {
        font-size: var(--font-size-xs);
        color: var(--color-text-tertiary);
    }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>