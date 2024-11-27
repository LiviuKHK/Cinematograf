<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'includes/db_connection.php';
$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titlu = $_POST['titlu'];
    $gen = $_POST['gen'];
    $an_lansare = $_POST['an_lansare'];
    $descriere = $_POST['descriere'];

    if (!empty($titlu) && !empty($gen) && !empty($an_lansare) && !empty($descriere)) {
   
        $stmt = $conn->prepare("INSERT INTO filme (titlu, gen, an_lansare, descriere) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $titlu, $gen, $an_lansare, $descriere);
        
        if ($stmt->execute()) {
            echo "Film adăugat cu succes!";
        } else {
            echo "A apărut o eroare la adăugarea filmului!";
        }
        $stmt->close();
    } else {
        echo "Toate câmpurile sunt obligatorii!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adaugă film</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Adaugă un nou film</h1>
    <form method="POST" action="add_movie.php">
        <label for="titlu">Titlu:</label>
        <input type="text" id="titlu" name="titlu" required><br>

        <label for="gen">Gen:</label>
        <input type="text" id="gen" name="gen" required><br>

        <label for="an_lansare">An lansare:</label>
        <input type="text" id="an_lansare" name="an_lansare" required><br>

        <label for="descriere">Descriere:</label>
        <textarea id="descriere" name="descriere" required></textarea><br>

        <button type="submit">Adaugă film</button>
    </form>
</body>
</html>
