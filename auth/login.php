<?php
/*
 * User Login Page
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Authenticates users and creates a session
 */

session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit;
}

require_once __DIR__ . '/../includes/database.php';

$page_title = 'Log In';
$errors = [];
$form_email = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Store email for repopulating form
    $form_email = $email;

    // Basic validation
    if ($email === '') {
        $errors[] = 'Email is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    // Attempt to authenticate
    if (empty($errors)) {
        $user = db_query_one("SELECT id, name, email, password_hash FROM users WHERE email = ?", [$email]);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Authentication successful - create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            // Redirect to dashboard
            header('Location: ../dashboard.php');
            exit;
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Log in to access your recipes and meal plans</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="message message-error">
                <?php foreach ($errors as $error): ?>
                    <div>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com"
                    value="<?= htmlspecialchars($form_email) ?>" required />
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input"
                    placeholder="Enter your password" required />
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Log In
            </button>
        </form>

        <div class="auth-footer">
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </div>
    </div>
</div>

<!-- Demo credentials hint -->
<div class="text-center mt-lg">
    <p class="text-secondary" style="font-size: 13px;">
        Demo account: <strong>test@example.com</strong> / <strong>password123</strong>
    </p>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>