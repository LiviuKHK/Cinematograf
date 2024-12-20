<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Nu ești autentificat!");
}

include 'includes/db_connection.php';
$conn = db_connect();

if (!isset($_GET['program_id'])) {
    die("ID-ul programării nu a fost specificat!");
}

$program_id = $_GET['program_id'];
$user_id = $_SESSION['user_id'];

if (!filter_var($program_id, FILTER_VALIDATE_INT)) {
    die("ID invalid!");
}

try {
    $stmt = $conn->prepare("SELECT * FROM programari WHERE user_id = :user_id AND programare_id = :program_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':program_id', $program_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "Ai deja o programare pentru acest film!";
    } else {
        $stmt = $conn->prepare("INSERT INTO programari (user_id, programare_id) VALUES (:user_id, :program_id)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':program_id', $program_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "Programarea ta a fost realizată cu succes!";
    }
} catch (PDOException $e) {
    echo "Eroare la procesarea cererii: " . $e->getMessage();
}
?>
