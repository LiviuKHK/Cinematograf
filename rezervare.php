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

try {
    $stmt_rezervare = $conn->prepare("INSERT INTO rezervari (programare_id, utilizator_id) VALUES (?, ?)");
    $stmt_rezervare->bindParam(1, $programare_id, PDO::PARAM_INT);
    $stmt_rezervare->bindParam(2, $user_id, PDO::PARAM_INT);

    if ($stmt_rezervare->execute()) {
        echo "Rezervarea a fost efectuată cu succes!";
    } else {
        echo "Eroare la efectuarea rezervării.";
    }
} catch (PDOException $e) {
    echo "Eroare: " . $e->getMessage();
}

$conn = null;
?>
