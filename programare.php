<?php
$program_id = $_GET['program_id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM programari WHERE user_id = ? AND program_id = ?");
$stmt->bind_param("ii", $user_id, $program_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Ai deja o programare pentru acest film!";
} else {
    $stmt = $conn->prepare("INSERT INTO programari (user_id, program_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $program_id);
    $stmt->execute();
    echo "Programarea ta a fost realizată cu succes!";
}
?>
