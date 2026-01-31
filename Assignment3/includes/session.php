<?php
require_once __DIR__ . '/config.php';
// Session management functions
// Handles secure session initialization and role checking
// 
// Author: Samar Khajuria
// Student ID: T00714740

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  // Configure session cookie for security
  session_set_cookie_params([
    'lifetime' => 0,        // Cookie expires when browser closes
    'httponly' => true,     // Prevent JavaScript access
    'samesite' => 'Lax',    // CSRF protection
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
  ]);
  
  session_start();
}

/**
 * Get the current user's role from session
 * @return string|null Role name ('admin', 'tech', 'customer') or null
 */
function current_user_role(): ?string {
  return $_SESSION['role'] ?? null;
}

/**
 * Check if user has required role, redirect if not
 * @param string $role Required role
 */
function require_role(string $role): void {
  if (($_SESSION['role'] ?? null) !== $role) {
    redirect_to('index.php');
  }
}
?>
