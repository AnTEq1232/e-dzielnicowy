<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eDzielnicowy - Strona główna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: #fff;
            padding: 15px 20px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        nav {
            background-color: #34495e;
            padding: 10px 20px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin-right: 15px;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 20px;
        }

        .welcome {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #2980b9;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }

        .button:hover {
            background-color: #3498db;
        }
    </style>
</head>
<body>

<header>
    <h1>eDzielnicowy</h1>
</header>

<nav>
    <a href="index.php">Strona główna</a>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="my_profile.php">Mój profil</a>
        <a href="logout.php">Wyloguj</a>
    <?php else: ?>
        <a href="login.php">Logowanie</a>
        <a href="register.php">Rejestracja</a>
    <?php endif; ?>
</nav>

<div class="container">
    <div class="welcome">
        <?php if(isset($_SESSION['user_id'])): ?>
            <h2>Witaj!</h2>
            <p>Jesteś zalogowany. Możesz przeglądać swój profil i zgłoszenia.</p>
        <?php else: ?>
            <h2>Witaj na portalu eDzielnicowy!</h2>
            <p>Portal umożliwia szybkie zgłaszanie zdarzeń w Twojej okolicy. Aby korzystać z pełnej funkcjonalności, zaloguj się lub zarejestruj.</p>
        <?php endif; ?>
    </div>

    <?php if(!isset($_SESSION['user_id'])): ?>
        <a href="register.php" class="button">Zarejestruj się</a>
        <a href="login.php" class="button">Zaloguj się</a>
    <?php else: ?>
        <a href="my_profile.php" class="button">Mój profil</a>
        <a href="logout.php" class="button">Wyloguj</a>
    <?php endif; ?>
</div>

</body>
</html>