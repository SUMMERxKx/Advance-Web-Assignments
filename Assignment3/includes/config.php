<?php
// Configuration file for SportsPro Technical Support
// Handles path detection and URL generation
// 
// Author: Samar Khajuria
// Student ID: T00714740

// Debug mode - set to true during development to see detailed error messages
define('APP_DEBUG', false);

// Database configuration
// Modify these values to match your MySQL setup
// For MAMP with default port: leave DB_PORT empty or set to 3306
// For MAMP with port 8889: set DB_PORT to 8889 (MAMP default)
if (!defined('DB_HOST')) {
	define('DB_HOST', 'localhost');
}
if (!defined('DB_PORT')) {
	// MAMP typically uses port 8889. Change to '' or '3306' if using standard MySQL port
	define('DB_PORT', '8889'); // Empty string means use default port (3306)
}
if (!defined('DB_NAME')) {
	define('DB_NAME', 'tech_support');
}
if (!defined('DB_USER')) {
	define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
	// MAMP sometimes uses 'root' as password. Change to '' if no password
	define('DB_PASS', 'root');
}

// Figure out where the app is installed on the filesystem
if (!defined('APP_ROOT')) {
	$rootPath = realpath(__DIR__ . '/..');
	define('APP_ROOT', $rootPath !== false ? $rootPath : __DIR__ . '/..');
}

// Calculate the base URL path relative to document root
if (!defined('APP_BASE_PATH')) {
	$docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
	$docPath = $docRoot !== '' ? realpath($docRoot) : false;
	$appPath = realpath(APP_ROOT) ?: APP_ROOT;

	$base = '';
	if ($docPath && strpos($appPath, $docPath) === 0) {
		$base = str_replace('\\', '/', substr($appPath, strlen($docPath)));
	}

	$base = '/' . ltrim($base, '/');
	if ($base === '/') {
		$base = '';
	}

	define('APP_BASE_PATH', $base);
}

// Helper function to build application URLs
if (!function_exists('app_url')) {
	function app_url(string $path = ''): string {
		$base = APP_BASE_PATH;
		$cleanPath = ltrim($path, '/');

		if ($cleanPath === '') {
			return $base === '' ? '/' : $base . '/';
		}

		return ($base === '' ? '' : $base) . '/' . $cleanPath;
	}
}

// Helper for asset URLs (CSS, JS, images, etc.)
if (!function_exists('asset_url')) {
	function asset_url(string $path): string {
		return app_url($path);
	}
}

// Redirect helper that respects the base path
if (!function_exists('redirect_to')) {
	function redirect_to(string $path): void {
		header('Location: ' . app_url($path));
		exit;
	}
}
