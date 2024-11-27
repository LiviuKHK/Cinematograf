<?php
session_start();
include 'includes/db_connection.php';

$conn = db_connect();

if (!isset($_SESSION['user_id'])) {
    die("Trebuie să fii autentificat pentru a-ți vedea rezervările.");
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT r.id AS rezervare_id, f.titlu, p.data 
                        FROM rezervari r
                        JOIN programari p ON r.programare_id = p.programare_id
                        JOIN filme f ON p.film_id = f.film_id
                        WHERE r.utilizator_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervările mele</title>
</head>
<body>
    <h1>Rezervările mele</h1>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Film</th>
                    <th>Data</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['titlu']); ?></td>
                        <td><?php echo htmlspecialchars($row['data']); ?></td>
                        <td>
                            <a href="anulare_rezervare.php?rezervare_id=<?php echo $row['rezervare_id']; ?>">Anulează</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nu ai rezervări efectuate.</p>
    <?php endif; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
