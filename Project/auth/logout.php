<?php
/*
 * User Logout Page
 * Recipe Management and Meal Planner
 * Author: Samar Khajuria
 * Student ID: T00714740
 * 
 * Destroys the user session and redirects to home page
 */

session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destroy the session
session_destroy();

// Redirect to home page
header('Location: ../index.php');
exit;
?>