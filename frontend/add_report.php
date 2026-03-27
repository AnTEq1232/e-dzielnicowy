<?php
session_start();
include "config.php";

// Sprawdzenie, czy użytkownik zalogowany
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Obsługa formularza
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $event_date = $_POST['event_date'];
    $user_id = $_SESSION['user_id'];

    $attachment_id = NULL;

    // Obsługa uploadu pliku
    if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0){
        $upload_dir = 'images/';
        $filename = time() . '_' . basename($_FILES['attachment']['name']);
        $target_file = $upload_dir . $filename;

        if(move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)){
            // zapis do tabeli Attachments
            $stmt_attach = $conn->prepare("INSERT INTO Attachments (file_path) VALUES (?)");
            $stmt_attach->bind_param("s", $target_file);
            if($stmt_attach->execute()){
                $attachment_id = $stmt_attach->insert_id;
            }
            $stmt_attach->close();
        } else {
            echo "Błąd przy przesyłaniu pliku.";
        }
    }

    // zapis zgłoszenia
    $stmt = $conn->prepare("INSERT INTO Reports (title, description, category, location, event_date, attachment_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $title, $description, $category, $location, $event_date, $attachment_id, $user_id);

    if($stmt->execute()){
        echo "<p>Zgłoszenie dodane pomyślnie!</p>";
        header("Location: my_profile.php");
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
    <title>Dodaj zgłoszenie - eDzielnicowy</title>
</head>
<body>

<h2>Dodaj zgłoszenie</h2>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Tytuł" required><br><br>
    <textarea name="description" placeholder="Opis zdarzenia" required></textarea><br><br>
    <input type="text" name="category" placeholder="Kategoria" required><br><br>
    <input type="text" name="location" placeholder="Lokalizacja" required><br><br>
    <input type="date" name="event_date" required><br><br>
    <input type="file" name="attachment"><br><br>
    <button type="submit">Dodaj zgłoszenie</button>
</form>

<a href="my_profil.php">Profil</a> | <a href="logout.php">Wyloguj</a>

</body>
</html>