<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'includes/db_connection.php';

$conn = db_connect();

$stmt_users = $conn->prepare("SELECT rol, COUNT(*) as numar FROM utilizatori GROUP BY rol");
$stmt_users->execute();
$result_users = $stmt_users->get_result();
$users_report = [];
while ($row = $result_users->fetch_assoc()) {
    $users_report[$row['rol']] = $row['numar'];
}
$stmt_users->close();

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
$result_filme = $stmt_filme->get_result();
$filme_report = [];
while ($row = $result_filme->fetch_assoc()) {
    $filme_report[] = $row;
}
$stmt_filme->close();

$stmt_rezervari = $conn->prepare("SELECT COUNT(*) as total_rezervari FROM rezervari");
$stmt_rezervari->execute();
$total_rezervari = $stmt_rezervari->get_result()->fetch_assoc()['total_rezervari'];
$stmt_rezervari->close();

$conn->close();
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
