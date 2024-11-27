<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  
    exit;
}

include 'includes/db_connection.php';

$conn = db_connect();

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT nume FROM utilizatori WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $nume = $user['nume'];  
} else {
    echo "Utilizatorul nu a fost găsit!";
    exit;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utilizator</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Bine ai venit, <?php echo htmlspecialchars($nume); ?>!</h1>
    
    <p>Acesta este panoul de control pentru utilizatorii obișnuiți.</p>

    <ul>
        <li><a href="profile.php">Vezi profilul meu</a></li>
        <li><a href="logout.php">Ieși din cont</a></li>
    </ul>
    <h1>Filme disponibile</h1>
    <?php
    $stmt = $conn->prepare("SELECT * FROM filme");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($film = $result->fetch_assoc()) {
        echo "<p><a href='program.php?film_id=" . $film['film_id'] . "'>" . $film['titlu'] . "</a></p>";
    }
    $stmt->close();
    $conn->close();
    ?>

</body>
</html>
