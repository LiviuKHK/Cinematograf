<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  
    exit;
}

if ($_SESSION['rol'] !== 'admin') {
    header("Location: user_dashboard.php");  
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
$conn->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Bine ai venit, Admin <?php echo htmlspecialchars($nume); ?>!</h1>
    
    <p>Acesta este panoul de control pentru administratori.</p>

    <ul>
        <li><a href="profile.php">Vezi profilul meu</a></li>
        <li><a href="rapoarte.php">Vizualizare rapoarte</a></li>
        <li><a href="manage_users.php">Gestionare utilizatori</a></li>
        <li><a href="add_movie.php">Adaugă film</a></li>
        <li><a href="logout.php">Ieși din cont</a></li>
    </ul>
</body>
</html>
