<?php
include 'includes/db_connection.php';

$conn = db_connect();

if (!isset($_GET['film_id'])) {
    die("ID-ul filmului nu a fost specificat!");
}

$film_id = $_GET['film_id'];

if (!filter_var($film_id, FILTER_VALIDATE_INT)) {
    die("ID invalid!");
}

try {
    $stmt_film = $conn->prepare("SELECT * FROM filme WHERE film_id = :film_id");
    $stmt_film->bindParam(':film_id', $film_id, PDO::PARAM_INT);
    $stmt_film->execute();
    $film = $stmt_film->fetch(PDO::FETCH_ASSOC);

    if (!$film) {
        die("Filmul nu a fost găsit!");
    }

    $stmt_programari = $conn->prepare("
        SELECT p.programare_id, p.data, s.nume_sala, s.capacitate
        FROM programari p
        JOIN sali s ON p.sala_id = s.sala_id
        WHERE p.film_id = :film_id
    ");
    $stmt_programari->bindParam(':film_id', $film_id, PDO::PARAM_INT);
    $stmt_programari->execute();
    $programari = $stmt_programari->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Eroare la procesarea cererii: " . $e->getMessage());
}

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

    <?php if (count($programari) > 0): ?>
        <table border="1">
            <tr>
                <th>Locație</th>
                <th>Data și Ora</th>
                <th>Capacitate Sală</th>
                <th>Acțiune</th>
            </tr>
            <?php foreach ($programari as $program): ?>
                <tr>
                    <td><?php echo htmlspecialchars($program['nume_sala']); ?></td>
                    <td><?php echo htmlspecialchars($program['data']); ?></td>
                    <td><?php echo htmlspecialchars($program['capacitate']); ?></td>
                    <td>
                        <a href="rezervare.php?programare_id=<?php echo $program['programare_id']; ?>">Rezervă loc</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nu există programări disponibile pentru acest film.</p>
    <?php endif; ?>
</body>
</html>

