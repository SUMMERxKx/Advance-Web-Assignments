<?php
// Authentication and security functions
// Handles HTTPS enforcement and other security features
// 
// Author: Samar Khajuria
// Student ID: T00714740

// Set to true to force HTTPS (useful in production)
// Keep false for local development
const ENFORCE_HTTPS = false;

// Redirect to HTTPS if required
if (ENFORCE_HTTPS) {
  $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') == 443;
  
  if (!$isSecure) {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $uri  = $_SERVER['REQUEST_URI'] ?? '/';
    header("Location: https://{$host}{$uri}");
    exit;
  }
}
?>
