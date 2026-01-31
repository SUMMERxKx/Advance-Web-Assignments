<?php
/*
 * Header Template
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Calculates relative paths for CSS and navigation based on current script location
 */

// Calculate relative path from current script to project root
$script_dir = dirname($_SERVER['SCRIPT_FILENAME']);
$app_root = dirname(__DIR__);

$rel_path = '';
if ($script_dir !== $app_root) {
    $script_normalized = str_replace('\\', '/', $script_dir);
    $app_normalized = str_replace('\\', '/', $app_root);

    if (strpos($script_normalized, $app_normalized) === 0) {
        $diff = substr($script_normalized, strlen($app_normalized));
        $depth = substr_count($diff, '/');
        if ($depth > 0) {
            $rel_path = str_repeat('../', $depth);
        }
    }
}

$css_path = $rel_path . 'main.css';
$home_path = $rel_path . 'index.php';
$dashboard_path = $rel_path . 'dashboard.php';
$recipes_path = $rel_path . 'recipes/list.php';
$meals_path = $rel_path . 'meals/planner.php';
$login_path = $rel_path . 'auth/login.php';
$register_path = $rel_path . 'auth/register.php';
$logout_path = $rel_path . 'auth/logout.php';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        <?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>Recipe Planner
    </title>
    <link rel="stylesheet" href="<?= htmlspecialchars($css_path) ?>" />
</head>

<body>
    <header class="site-header">
        <div class="header-content">
            <div class="brand">
                <a href="<?= htmlspecialchars($home_path) ?>" class="logo">
                    <span class="logo-icon">🍳</span>
                    <span class="logo-text">Recipe Planner</span>
                </a>
            </div>

            <nav class="main-nav">
                <?php if ($is_logged_in): ?>
                    <a href="<?= htmlspecialchars($dashboard_path) ?>" class="nav-link">Dashboard</a>
                    <a href="<?= htmlspecialchars($recipes_path) ?>" class="nav-link">My Recipes</a>
                    <a href="<?= htmlspecialchars($meals_path) ?>" class="nav-link">Meal Planner</a>
                    <div class="nav-divider"></div>
                    <span class="user-greeting">Hi,
                        <?= htmlspecialchars($user_name) ?>
                    </span>
                    <a href="<?= htmlspecialchars($logout_path) ?>" class="nav-link nav-link-secondary">Logout</a>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($login_path) ?>" class="nav-link">Login</a>
                    <a href="<?= htmlspecialchars($register_path) ?>" class="btn btn-primary btn-sm">Sign Up</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="main-content">