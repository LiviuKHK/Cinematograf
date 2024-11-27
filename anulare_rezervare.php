<?php
session_start();
include 'includes/db_connection.php';

$conn = db_connect();

if (!isset($_SESSION['user_id'])) {
    die("Trebuie să fii autentificat pentru a anula o rezervare.");
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['rezervare_id'])) {
    die("ID-ul rezervării nu a fost specificat!");
}

$rezervare_id = $_GET['rezervare_id'];

$stmt_check = $conn->prepare("SELECT * FROM rezervari WHERE id = ? AND utilizator_id = ?");
$stmt_check->bind_param("ii", $rezervare_id, $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $stmt_delete = $conn->prepare("DELETE FROM rezervari WHERE id = ?");
    $stmt_delete->bind_param("i", $rezervare_id);
    if ($stmt_delete->execute()) {
        echo "Rezervare anulată cu succes!";
    } else {
        echo "A apărut o eroare la anularea rezervării.";
    }
    $stmt_delete->close();
} else {
    echo "Nu ai dreptul să anulezi această rezervare.";
}

$stmt_check->close();
$conn->close();
?>
