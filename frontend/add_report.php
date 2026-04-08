<?php
// Handle form and session at top
if (session_status() == PHP_SESSION_NONE) session_start();
require_once 'config.php';

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
            $upload_error = "Błąd przy przesyłaniu pliku.";
        }
    }

    // zapis zgłoszenia
    $stmt = $conn->prepare("INSERT INTO Reports (title, description, category, location, event_date, attachment_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $title, $description, $category, $location, $event_date, $attachment_id, $user_id);

    if($stmt->execute()){
        header("Location: my_profile.php");
        exit();
    } else {
        $form_error = "Błąd: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

$page_title = 'Dodaj zgłoszenie - eDzielnicowy';
require_once 'header.php';
?>

<div class="welcome" style="max-width:700px; margin:0 auto;">
    <h2>Dodaj zgłoszenie</h2>

    <?php if(!empty($upload_error)): ?><div class="error"><?= htmlspecialchars($upload_error) ?></div><?php endif; ?>
    <?php if(!empty($form_error)): ?><div class="error"><?= htmlspecialchars($form_error) ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Tytuł" required>

        <textarea name="description" placeholder="Opis zdarzenia" required></textarea>

        <input type="text" name="category" placeholder="Kategoria" required>

        <input type="text" name="location" placeholder="Lokalizacja" required>

        <input type="date" name="event_date" required>

        <input type="file" name="attachment">

        <button type="submit" class="button">Dodaj zgłoszenie</button>
    </form>

    <div class="links" style="margin-top:12px;">
        <a href="my_profile.php">Profil</a>
        <a href="logout.php">Wyloguj</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
