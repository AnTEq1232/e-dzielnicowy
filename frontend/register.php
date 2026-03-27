<?php
include "config.php"; // teraz uruchamia sesję tylko jeśli jeszcze nie działa

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST["user_name"];
    $password = password_hash($_POST["user_password"], PASSWORD_DEFAULT);
    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $conn->prepare("INSERT INTO Users (username, password, ip_address) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $ip);

    if($stmt->execute()){
        // automatyczne logowanie
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        echo "Błąd: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>eDzielnicowy - Rejestracja</title>
</head>
<body>

<h2>Rejestracja</h2>

<form method="post">
    <input type="text" name="user_name" placeholder="Login" required><br><br>
    <input type="password" name="user_password" placeholder="Hasło" required><br><br>
    <button type="submit">Zarejestruj</button>
</form>

<a href="login.php">Masz konto? Zaloguj się</a>

</body>
</html>