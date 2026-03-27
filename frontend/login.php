<?php
include "config.php"; // config.php obsługuje już sesję i połączenie z DB
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eDzielnicowy - Logowanie</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>
    <div id="container">
        <h2>Logowanie</h2>
        <form method="POST">
            <input type="text" name="login" id="login" placeholder="Login:" required>
            <input type="password" name="password" id="password" placeholder="Hasło:" required>
            <input type="submit" id="loginBtn" value="Zaloguj się">
        </form>

        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $login = $_POST["login"];
            $password = $_POST["password"];

            // przygotowane zapytanie, aby uniknąć SQL injection
            $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if(password_verify($password, $user["password"])) {
                    // zapis do sesji
                    $_SESSION["user_id"] = $user["user_id"];
                    $_SESSION["username"] = $user["username"];
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Nieprawidłowe hasło";
                }
            } else {
                $error = "Użytkownik nie istnieje";
            }

            $stmt->close();
        }

        if (isset($error)) {
            echo "<p style='color:red;'>$error</p>";
        }
        ?>
    </div>
</body>
</html>