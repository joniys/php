<?php require_once __DIR__ . '/bootstrap.php';
$pageTitle = $pageTitle ?? 'French Riviera';
$active = $active ?? ''; ?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> | French Riviera</title>
<meta name="description" content="French Riviera – refined French cuisine on the Côte d'Azur.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="<?= e($active) ?>">
<header class="site-header">
  <a class="brand" href="index.php">French <span>Riviera</span></a>
<?php if (current_user()): ?>
  <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false"><span></span><span></span><span></span></button>
  <nav class="main-nav" id="mainNav">
    <a href="index.php" class="<?= $active === 'home' ? 'on' : '' ?>">Home</a>
    <a href="menu.php" class="<?= $active === 'menu' ? 'on' : '' ?>">Menu</a>
    <a href="about.php" class="<?= $active === 'about' ? 'on' : '' ?>">About Us</a>
    <a href="contact.php" class="<?= $active === 'contact' ? 'on' : '' ?>">Contact</a>
    <?php if (is_admin()): ?>
    <a href="admin.php" class="<?= $active === 'admin' ? 'on' : '' ?>">Admin</a>
    <?php endif; ?>
    <a href="contact.php#reserve" class="btn btn-gold btn-sm">Reserve a Table</a>
    <a href="logout.php" class="logout" title="Signed in as <?= e(current_user()['name']) ?>">Logout</a>
  </nav>
<?php endif; ?>
</header>
<main>
