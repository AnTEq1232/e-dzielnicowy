<?php
$page_title = 'eDzielnicowy - Rejestracja';
require_once 'header.php';

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = $_POST["user_name"];
    $password_raw = $_POST["user_password"];

    if(strlen($username) < 3){
        $error = "Login musi mieć min. 3 znaki";
    }
    elseif(strlen($password_raw) < 6){
        $error = "Hasło musi mieć min. 6 znaków";
    }
    else{

        $stmt = $conn->prepare("SELECT user_id FROM Users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $error = "Taki login już istnieje";
        }
        else{
            $password = password_hash($password_raw, PASSWORD_DEFAULT);

            $ip = $_SERVER['REMOTE_ADDR'];
            

            $stmt = $conn->prepare("INSERT INTO Users (username, password, ip_address) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $ip);

            if($stmt->execute()){
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;

                header("Location: index.php");
                exit();
            }
            else{
                $error = "Błąd bazy danych";
            }
        }

        $stmt->close();
    }
}
?>

<div class="welcome" style="max-width:420px; margin:0 auto;">
    <a href="index.php" class="back-btn">← Strona główna</a>
    <h2>Rejestracja</h2>

    <?php if($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="user_name" placeholder="Login" required>
        <input type="password" name="user_password" placeholder="Hasło" required>
        <button type="submit">Zarejestruj</button>
    </form>

    <div class="login-link">
        Masz konto? <a href="login.php">Zaloguj się</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>