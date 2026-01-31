<?php
/*
 * Database Error Page
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Displays a user-friendly error message when database operations fail
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Database Error - Recipe Planner</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f5f5f7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .error-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            max-width: 480px;
            width: 100%;
            padding: 48px 40px;
            text-align: center;
        }

        .error-icon {
            width: 64px;
            height: 64px;
            background: #ffebee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 32px;
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1d1d1f;
            margin-bottom: 12px;
        }

        .message {
            color: #86868b;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .error-details {
            background: #f5f5f7;
            border-radius: 8px;
            padding: 16px;
            font-family: "SF Mono", Monaco, Consolas, monospace;
            font-size: 13px;
            color: #d32f2f;
            text-align: left;
            word-break: break-word;
            margin-bottom: 24px;
        }

        .back-link {
            display: inline-block;
            color: #007aff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: opacity 0.2s ease;
        }

        .back-link:hover {
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <div class="error-card">
        <div class="error-icon">⚠️</div>
        <h1>Database Error</h1>
        <p class="message">
            We encountered a problem connecting to the database.
            Please check your configuration and try again.
        </p>
        <?php if (isset($error_message)): ?>
            <div class="error-details">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>
        <a href="javascript:history.back()" class="back-link">← Go Back</a>
    </div>
</body>

</html>