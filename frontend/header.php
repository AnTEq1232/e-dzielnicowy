<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';

// Allow pages to set $page_title before including header
if (!isset($page_title)) {
    $page_title = 'eDzielnicowy';
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>eDzielnicowy</h1>
</header>

<nav>
    <a href="index.php">Strona główna</a>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="my_profile.php">Mój profil</a>
        <a href="my_reports.php">Moje zgłoszenia</a>
        <a href="add_report.php">Zgłoś zdarzenie</a>
        <a href="logout.php">Wyloguj</a>
    <?php else: ?>
        <a href="login.php">Logowanie</a>
        <a href="register.php">Rejestracja</a>
    <?php endif; ?>
</nav>

<div class="container">
