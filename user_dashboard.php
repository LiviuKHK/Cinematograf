<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  
    exit;
}

include 'includes/db_connection.php';

$conn = db_connect();

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT nume FROM utilizatori WHERE id = ?");
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $nume = $user['nume'];  
    } else {
        echo "Utilizatorul nu a fost găsit!";
        exit;
    }

    $stmt->close();
} catch (PDOException $e) {
    die("Eroare la obținerea datelor utilizatorului: " . $e->getMessage());
}
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
    try {
        $stmt = $conn->prepare("SELECT * FROM filme");
        $stmt->execute();
        $filme = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($filme as $film) {
            echo "<p><a href='program.php?film_id=" . $film['film_id'] . "'>" . htmlspecialchars($film['titlu']) . "</a></p>";
        }

        $stmt->close();
    } catch (PDOException $e) {
        echo "Eroare la obținerea filmelor: " . $e->getMessage();
    }

    $conn = null;
    ?>

</body>
</html>
