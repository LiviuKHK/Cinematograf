<?php
include 'includes/db_connection.php';

$conn = db_connect();

if (!isset($_GET['film_id'])) {
    die("ID-ul filmului nu a fost specificat!");
}

$film_id = $_GET['film_id'];

$stmt_film = $conn->prepare("SELECT * FROM filme WHERE film_id = ?");
$stmt_film->bind_param("i", $film_id);
$stmt_film->execute();
$result_film = $stmt_film->get_result();

if ($result_film->num_rows > 0) {
    $film = $result_film->fetch_assoc();
} else {
    die("Filmul nu a fost găsit!");
}

$stmt_programari = $conn->prepare("
    SELECT p.programare_id, p.data, s.nume_sala, s.capacitate
    FROM programari p
    JOIN sali s ON p.sala_id = s.sala_id
    WHERE p.film_id = ?
");
$stmt_programari->bind_param("i", $film_id);
$stmt_programari->execute();
$result_programari = $stmt_programari->get_result();

$stmt_film->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Film</title>
</head>
<body>
    <h1>Program pentru filmul: <?php echo htmlspecialchars($film['titlu']); ?></h1>

    <?php if ($result_programari->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Locație</th>
                <th>Data și Ora</th>
                <th>Capacitate Sală</th>
                <th>Acțiune</th>
            </tr>
            <?php while ($program = $result_programari->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($program['nume_sala']); ?></td>
                    <td><?php echo htmlspecialchars($program['data']); ?></td>
                    <td><?php echo htmlspecialchars($program['capacitate']); ?></td>
                    <td>
                        <a href="rezervare.php?programare_id=<?php echo $program['programare_id']; ?>">Rezervă loc</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Nu există programări disponibile pentru acest film.</p>
    <?php endif; ?>
</body>
</html>