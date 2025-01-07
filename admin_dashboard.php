<?php
session_start();

include 'includes/db_connection.php';

$conn = db_connect();

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT nume FROM utilizatori WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $nume = htmlspecialchars($user['nume']);  
} else {
    echo "Utilizatorul nu a fost găsit!";
    exit;
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>Bine ai venit, Admin <?php echo $nume; ?>!</h1>
    
    <ul>
        <li><a href="profile.php">Vezi profilul meu</a></li>
        <li><a href="rapoarte.php">Vizualizare rapoarte</a></li>
        <li><a href="manage_users.php">Gestionare utilizatori</a></li>
        <li><a href="add_movie.php">Adaugă film</a></li>
        <li><a href="logout.php">Ieși din cont</a></li>
    </ul>

    <h2>Vizualizează graficul cu utilizatori</h2>
    <form method="get" action="users_chart.php">
        <button type="submit">Vezi graficul utilizatorilor</button>
    </form>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
