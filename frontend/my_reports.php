
<?php 
    include "config.php";

    if(!isset($_SESSION["user_id"])) {
        echo "Musisz być zalogowany!";
        exit();
    }

    $user_id = $_SESSION["user_id"];
    $sql = $conn->prepare("SELECT * FROM reports WHERE user_id = ?");
    $sql->bind_param("i", $user_id);
    $sql->execute();
    $result = $sql->get_result();
?>
<?php
$page_title = 'Moje zgłoszenia';
require_once 'header.php';

echo '<h2>Moje zgłoszenia</h2>';

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="welcome">';
        echo '<h3>Tytuł: ' . htmlspecialchars($row["title"]) . '</h3>';
        echo '<p>Opis: ' . nl2br(htmlspecialchars($row["description"])) . '</p>';
        echo '<p>Kategoria: ' . htmlspecialchars($row["category"]) . '</p>';
        echo '<p>Lokacja: ' . htmlspecialchars($row["location"]) . '</p>';
        echo '<p>Czas wydarzenia: ' . htmlspecialchars($row["event_date"]) . '</p>';
        echo '<p class="muted">Czas zgłoszenia: ' . htmlspecialchars($row["created_at"]) . '</p>';
        echo '</div>';
    }
} else {
    echo '<p>Nie masz jeszcze żadnych zgłoszeń.</p>';
}

require_once 'footer.php';

