<?php
include "config.php"; // config.php już obsługuje sesję i połączenie z DBa

// Sprawdzenie, czy użytkownik jest zalogowany
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Pobranie danych użytkownika
$stmt_user = $conn->prepare("SELECT username, ip_address, created_at FROM Users WHERE user_id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$stmt_user->close();

// Pobranie ostatnich zgłoszeń użytkownika (ostatnie 10)
$stmt_reports = $conn->prepare("
    SELECT r.report_id, r.title, r.category, r.location, r.event_date, a.file_path 
    FROM Reports r
    LEFT JOIN Attachments a ON r.attachment_id = a.attachment_id
    WHERE r.user_id = ?
    ORDER BY r.event_date DESC
    LIMIT 10
");
$stmt_reports->bind_param("i", $user_id);
$stmt_reports->execute();
$result_reports = $stmt_reports->get_result();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Profil użytkownika - eDzielnicowy</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin:0; padding:0; }
        header { background: #2c3e50; color: #fff; padding: 15px 20px; }
        header h1 { margin:0; font-size:24px; }
        nav { background: #34495e; padding: 10px 20px; }
        nav a { color:#fff; text-decoration:none; margin-right:15px; font-weight:bold; }
        nav a:hover { text-decoration:underline; }
        .container { padding:20px; }
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        table, th, td { border:1px solid #ccc; }
        th, td { padding:10px; text-align:left; }
        th { background:#2980b9; color:#fff; }
        .button { display:inline-block; padding:10px 15px; background:#2980b9; color:#fff; text-decoration:none; border-radius:4px; margin-top:10px; }
        .button:hover { background:#3498db; }
        img.report-img { max-width:100px; max-height:100px; }
    </style>
</head>
<body>

<header>
    <h1>eDzielnicowy - Profil użytkownika</h1>
</header>

<nav>
    <a href="index.php">Strona główna</a>
    <a href="add_report.php">Dodaj zgłoszenie</a>
    <a href="logout.php">Wyloguj</a>
</nav>

<div class="container">
    <h2>Twój profil</h2>
    <p><strong>Login:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>IP rejestracji:</strong> <?php echo htmlspecialchars($user['ip_address']); ?></p>
    <p><strong>Data rejestracji:</strong> <?php echo $user['created_at']; ?></p>

    <h3>Twoje ostatnie zgłoszenia</h3>
    <?php if($result_reports->num_rows > 0): ?>
        <table>
            <tr>
                <th>Tytuł</th>
                <th>Kategoria</th>
                <th>Lokalizacja</th>
                <th>Data zdarzenia</th>
                <th>Załącznik</th>
            </tr>
            <?php while($report = $result_reports->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($report['title']); ?></td>
                    <td><?php echo htmlspecialchars($report['category']); ?></td>
                    <td><?php echo htmlspecialchars($report['location']); ?></td>
                    <td><?php echo $report['event_date']; ?></td>
                    <td>
                        <?php if($report['file_path']): ?>
                            <a href="<?php echo htmlspecialchars($report['file_path']); ?>" target="_blank">
                                <img src="<?php echo htmlspecialchars($report['file_path']); ?>" class="report-img">
                            </a>
                        <?php else: ?>
                            Brak
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Nie dodałeś jeszcze żadnych zgłoszeń.</p>
    <?php endif; ?>

</div>

</body>
</html>

<?php
$stmt_reports->close();
$conn->close();
?>