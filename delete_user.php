<?php
include 'includes/db_connection.php';

$conn = db_connect();
$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM utilizatori WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo "Utilizator șters cu succes!";
} else {
    echo "A apărut o problemă la ștergerea utilizatorului!";
}
$stmt->close();
$conn->close();
?>
