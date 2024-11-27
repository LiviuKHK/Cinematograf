<?php
session_start();
include 'includes/db_connection.php';

$conn = db_connect();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['programare_id'])) {
    die("ID-ul programării nu a fost specificat!");
}

$programare_id = $_GET['programare_id'];
$user_id = $_SESSION['user_id'];

$stmt_rezervare = $conn->prepare("INSERT INTO rezervari (bilet_id, utilizator_id) VALUES (?, ?)");
$stmt_rezervare->bind_param("ii", $programare_id, $user_id);

if ($stmt_rezervare->execute()) {
    echo "Rezervarea a fost efectuată cu succes!";
} else {
    echo "Eroare la efectuarea rezervării: " . $conn->error;
}

$stmt_rezervare->close();
$conn->close();
?>
