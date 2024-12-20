<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'includes/db_connection.php';
$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Eroare CSRF - token invalid.");
    }

    $titlu = htmlspecialchars(trim($_POST['titlu']));
    $gen = htmlspecialchars(trim($_POST['gen']));
    $an_lansare = htmlspecialchars(trim($_POST['an_lansare']));
    $descriere = htmlspecialchars(trim($_POST['descriere']));

    if (!empty($titlu) && !empty($gen) && !empty($an_lansare) && !empty($descriere)) {
        $stmt = $conn->prepare("INSERT INTO filme (titlu, gen, an_lansare, descriere) VALUES (:titlu, :gen, :an_lansare, :descriere)");
        $stmt->bindParam(':titlu', $titlu);
        $stmt->bindParam(':gen', $gen);
        $stmt->bindParam(':an_lansare', $an_lansare);
        $stmt->bindParam(':descriere', $descriere);

        if ($stmt->execute()) {
            echo "Film adăugat cu succes!";
        } else {
            echo "A apărut o eroare la adăugarea filmului!";
        }
    } else {
        echo "Toate câmpurile sunt obligatorii!";
    }

    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$conn = null;
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
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

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
