<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// połączenie z bazą
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ed";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}
?>