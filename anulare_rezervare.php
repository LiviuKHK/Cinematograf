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

$rezervare_id = htmlspecialchars($_GET['rezervare_id']); 

$stmt_check = $conn->prepare("SELECT * FROM rezervari WHERE id = :rezervare_id AND utilizator_id = :user_id");
$stmt_check->bindParam(':rezervare_id', $rezervare_id, PDO::PARAM_INT);
$stmt_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_check->execute();
$result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

if ($result_check) {
    $stmt_delete = $conn->prepare("DELETE FROM rezervari WHERE id = :rezervare_id");
    $stmt_delete->bindParam(':rezervare_id', $rezervare_id, PDO::PARAM_INT);
    if ($stmt_delete->execute()) {
        echo "Rezervare anulată cu succes!";
    } else {
        echo "A apărut o eroare la anularea rezervării.";
    }
} else {
    echo "Nu ai dreptul să anulezi această rezervare.";
}

$stmt_check = null;
$stmt_delete = null;
$conn = null;
?>
