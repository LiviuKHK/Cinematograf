<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  
    exit;
}

include 'includes/db_connection.php';
$conn = db_connect();  

$user_id = $_SESSION['user_id'];

if (!filter_var($user_id, FILTER_VALIDATE_INT)) {
    die("ID invalid!");
}

$error_message = "";

try {
    $stmt = $conn->prepare("SELECT nume, email, rol FROM utilizatori WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $nume = htmlspecialchars($user['nume'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');
        $rol = htmlspecialchars($user['rol'], ENT_QUOTES, 'UTF-8');
    } else {
        $error_message = "Utilizatorul nu a fost găsit!";
    }
} catch (PDOException $e) {
    $error_message = "Eroare la procesarea cererii. Te rugăm să încerci din nou.";
}

$conn = null;
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
    
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php else: ?>
        <p><strong>Nume:</strong> <?php echo $nume; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Rol:</strong> <?php echo $rol; ?></p>
    <?php endif; ?>

    <?php if ($_SESSION['rol'] === 'admin'): ?>
        <p><a href="admin_dashboard.php"><button>Înapoi</button></a></p>
    <?php else: ?>
        <p><a href="user_dashboard.php"><button>Înapoi</button></a></p>
    <?php endif; ?>
</body>
</html>
