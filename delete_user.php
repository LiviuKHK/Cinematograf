<?php
include 'includes/db_connection.php';

$conn = db_connect();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID-ul utilizatorului nu este valid!");
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM utilizatori WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo "Utilizator șters cu succes!";
} else {
    echo "A apărut o problemă la ștergerea utilizatorului!";
}

$stmt = null;
$conn = null;
?>
