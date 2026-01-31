<?php
/*
 * Home Page (Landing Page)
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Public entry point for the application
 */

session_start();

$page_title = 'Home';

include __DIR__ . '/includes/header.php';
?>

<div class="hero">
    <h1>Organize Your Recipes.<br>Plan Your Meals.</h1>
    <p>
        A simple, beautiful way to save your favorite recipes and plan
        what to cook throughout the week.
    </p>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="hero-actions">
            <a href="dashboard.php" class="btn btn-primary btn-lg">Go to Dashboard</a>
        </div>
    <?php else: ?>
        <div class="hero-actions">
            <a href="auth/register.php" class="btn btn-primary btn-lg">Get Started Free</a>
            <a href="auth/login.php" class="btn btn-secondary btn-lg">Log In</a>
        </div>
    <?php endif; ?>
</div>

<!-- Features Section -->
<section class="features-section">
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">📝</div>
            <h3>Save Recipes</h3>
            <p>Store all your favorite recipes in one place. Add ingredients, instructions, and prep time.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">📅</div>
            <h3>Plan Meals</h3>
            <p>Assign recipes to specific days. Plan breakfast, lunch, dinner, and snacks ahead.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">🔍</div>
            <h3>Find Quickly</h3>
            <p>Search and sort your recipes by name or prep time. Find what you need fast.</p>
        </div>
    </div>
</section>

<style>
    /* Features section styles */
    .features-section {
        padding: var(--space-2xl) 0;
        border-top: 1px solid var(--color-border-light);
        margin-top: var(--space-xl);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-lg);
    }

    .feature-card {
        text-align: center;
        padding: var(--space-xl) var(--space-lg);
        background: var(--color-white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border-light);
        transition: all 0.2s ease;
    }

    .feature-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .feature-icon {
        font-size: 40px;
        margin-bottom: var(--space-md);
    }

    .feature-card h3 {
        font-size: var(--font-size-lg);
        margin-bottom: var(--space-sm);
    }

    .feature-card p {
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
        margin: 0;
    }
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>