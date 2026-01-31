<?php
/*
 * User Registration Page
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Allows new users to create an account
 */

session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit;
}

require_once __DIR__ . '/../includes/database.php';

$page_title = 'Create Account';
$errors = [];
$success = false;
$form_data = [
    'name' => '',
    'email' => ''
];

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Store form data for repopulating
    $form_data['name'] = $name;
    $form_data['email'] = $email;

    // Validation
    if ($name === '') {
        $errors[] = 'Name is required.';
    } elseif (strlen($name) > 100) {
        $errors[] = 'Name must be less than 100 characters.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    // Check if email already exists
    if (empty($errors)) {
        $existing = db_query_one("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existing) {
            $errors[] = 'An account with this email already exists.';
        }
    }

    // Create account if no errors
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        db_execute(
            "INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)",
            [$name, $email, $password_hash]
        );

        $success = true;
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Create Account</h2>
            <p>Join Recipe Planner to start organizing your meals</p>
        </div>

        <?php if ($success): ?>
            <div class="message message-success">
                Account created successfully! <a href="login.php">Log in now</a>
            </div>
        <?php else: ?>

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
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Enter your name"
                        value="<?= htmlspecialchars($form_data['name']) ?>" required />
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com"
                        value="<?= htmlspecialchars($form_data['email']) ?>" required />
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input"
                        placeholder="At least 6 characters" required minlength="6" />
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input"
                        placeholder="Re-enter your password" required />
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Create Account
                </button>
            </form>

        <?php endif; ?>

        <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Log in</a></p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>