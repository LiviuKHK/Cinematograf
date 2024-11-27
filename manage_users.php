<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['rol'] !== 'admin') {
    header("Location: user_dashboard.php");
    exit;
}

include 'includes/db_connection.php';

$conn = db_connect();

$stmt = $conn->prepare("SELECT id, nume, email, rol FROM utilizatori");
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionare Utilizatori</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Gestionare Utilizatori</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nume</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['nume'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['rol'] . "</td>
                        <td>
                            <a href='edit_user.php?id=" . $row['id'] . "'>Editează</a> | 
                            <a href='delete_user.php?id=" . $row['id'] . "'>Șterge</a>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="admin_dashboard.php"><button>Înapoi</button></a>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
