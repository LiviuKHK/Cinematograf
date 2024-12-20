<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'includes/db_connection.php';

$conn = db_connect();

try {
    $stmt_users = $conn->prepare("SELECT rol, COUNT(*) as numar FROM utilizatori GROUP BY rol");
    $stmt_users->execute();
    $result_users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
    $users_report = [];
    foreach ($result_users as $row) {
        $users_report[$row['rol']] = $row['numar'];
    }

    $stmt_filme = $conn->prepare("
        SELECT f.titlu, COUNT(r.id) as numar_rezervari 
        FROM filme f
        LEFT JOIN programari p ON f.film_id = p.film_id
        LEFT JOIN bilete b ON p.programare_id = b.programare_id
        LEFT JOIN rezervari r ON b.bilet_id = r.bilet_id
        GROUP BY f.film_id
        ORDER BY numar_rezervari DESC
        LIMIT 5
    ");
    $stmt_filme->execute();
    $result_filme = $stmt_filme->fetchAll(PDO::FETCH_ASSOC);
    $filme_report = $result_filme;

    $stmt_rezervari = $conn->prepare("SELECT COUNT(*) as total_rezervari FROM rezervari");
    $stmt_rezervari->execute();
    $total_rezervari = $stmt_rezervari->fetch(PDO::FETCH_ASSOC)['total_rezervari'];

} catch (PDOException $e) {
    echo "Eroare la interogarea bazei de date: " . $e->getMessage();
    exit;
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vizualizare Rapoarte</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Raport Utilizatori</h2>
    <p>Număr total de utilizatori: <?php echo array_sum($users_report); ?></p>
    <ul>
        <?php foreach ($users_report as $rol => $numar): ?>
            <li><?php echo ucfirst($rol); ?>: <?php echo $numar; ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Filme Populare</h2>
    <p>Top 5 filme cu cele mai multe rezervări:</p>
    <ul>
        <?php foreach ($filme_report as $film): ?>
            <li><?php echo htmlspecialchars($film['titlu']); ?> - <?php echo $film['numar_rezervari']; ?> rezervări</li>
        <?php endforeach; ?>
    </ul>

    <h2>Rezervări Totale</h2>
    <p>Total rezervări efectuate: <?php echo $total_rezervari; ?></p>
    <a href="admin_dashboard.php">Înapoi la dashboard</a>
</body>
</html>
