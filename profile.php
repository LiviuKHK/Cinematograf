<?php
session_start();  

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  
    exit;
}

include 'includes/db_connection.php';
$conn = db_connect();  

$user_id = $_SESSION['user_id'];  
$stmt = $conn->prepare("SELECT nume, email, rol FROM utilizatori WHERE id = ?");
$stmt->bind_param("i", $user_id);  
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $nume = $user['nume'];
    $email = $user['email'];
    $rol = $user['rol'];
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
    <title>Profil Utilizator</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Profil Utilizator</h1>
    <p><strong>Nume:</strong> <?php echo htmlspecialchars($nume); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    <p><strong>Rol:</strong> <?php echo htmlspecialchars($rol); ?></p>

    <?php if ($_SESSION['rol'] === 'admin'): ?>
        <p><a href="admin_dashboard.php"><button>Înapoi</button></a></p>
    <?php else: ?>
        <p><a href="user_dashboard.php"><button>Înapoi</button></a></p>
    <?php endif; ?>
    
</body>
</html>
