<?php
$page_title = 'eDzielnicowy - Strona główna';
require_once 'header.php';
?>
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

    <?php
    // Sekcja: ostatnie 10 zgłoszeń
    $lastSql = "SELECT Reports.report_id, Reports.title, Reports.description, Reports.location, Reports.created_at, Users.username
                FROM Reports
                LEFT JOIN Users ON Reports.user_id = Users.user_id
                ORDER BY Reports.created_at DESC
                LIMIT 10";

    $lastRes = $conn->query($lastSql);

    echo '<div class="data-section">';
    echo '<h3>Ostatnie 10 zgłoszeń</h3>';

    if ($lastRes && $lastRes->num_rows > 0) {
        echo '<table class="data-table"><thead><tr><th>#</th><th>Tytuł</th><th>Lokalizacja</th><th>Użytkownik</th><th>Data</th></tr></thead><tbody>';
        $i = 0;
        while ($row = $lastRes->fetch_assoc()) {
            $i++;
            $title = htmlspecialchars($row['title']);
            $location = htmlspecialchars($row['location'] ?? '');
            $username = htmlspecialchars($row['username'] ?? 'Anonim');
            $date = htmlspecialchars($row['created_at']);
            echo '<tr>';
            echo '<td>' . $i . '</td>';
            echo '<td>' . $title . '</td>';
            echo '<td>' . $location . '</td>';
            echo '<td>' . $username . '</td>';
            echo '<td class="muted">' . $date . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p class="muted">Brak zgłoszeń do wyświetlenia.</p>';
    }

    // Sekcja: użytkownicy z największą ilością zgłoszeń
    $topSql = "SELECT Users.user_id, Users.username, COUNT(Reports.report_id) AS reports_count
               FROM Users
               LEFT JOIN Reports ON Users.user_id = Reports.user_id
               GROUP BY Users.user_id, Users.username
               HAVING COUNT(Reports.report_id) > 1
               ORDER BY reports_count DESC
               LIMIT 10";

    $topRes = $conn->query($topSql);

    echo '<h3 style="margin-top:18px">Użytkownicy z największą ilością zgłoszeń</h3>';
    if ($topRes && $topRes->num_rows > 0) {
        echo '<table class="data-table"><thead><tr><th>#</th><th>Użytkownik</th><th>Ilość zgłoszeń</th></tr></thead><tbody>';
        $rank = 0;
        while ($r = $topRes->fetch_assoc()) {
            $rank++;
            $uname = htmlspecialchars($r['username'] ?? 'Anonim');
            $count = (int)$r['reports_count'];
            echo '<tr>';
            echo '<td>' . $rank . '</td>';
            echo '<td>' . $uname . '</td>';
            echo '<td>' . $count . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p class="muted">Brak użytkowników lub zgłoszeń.</p>';
    }

    echo '</div>'; // .data-section
    ?>

<?php
require_once 'footer.php';
?>