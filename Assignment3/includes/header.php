<?php 
// Common header for all pages
// Includes session management and page structure
// 
// Author: Samar Khajuria
// Student ID: T00714740

require_once __DIR__ . '/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>SportsPro Technical Support</title>
  <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('main.css')) ?>" />
</head>
<body>
<header>
  <h1>SportsPro Technical Support</h1>
  <p>Sports management software for the sports enthusiast</p>
  <p>
  <a href="<?= htmlspecialchars(app_url('index.php')) ?>" class="home-button">Home</a>
  </p>
  <p>
    <?php if (!empty($_SESSION['role'])): ?>
      Logged in as <strong><?= htmlspecialchars($_SESSION['role']) ?></strong>
    <?php else: ?>
      Not logged in
    <?php endif; ?>
  </p>
</header>
<main>
