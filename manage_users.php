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

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            foreach ($result as $row) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['id']) . "</td>
                        <td>" . htmlspecialchars($row['nume']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['rol']) . "</td>
                        <td>
                            <a href='edit_user.php?id=" . htmlspecialchars($row['id']) . "'>Editează</a> | 
                            <form method='POST' action='manage_users.php' style='display:inline;'>
                                <input type='hidden' name='delete_id' value='" . htmlspecialchars($row['id']) . "'>
                                <input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>
                                <button type='submit' onclick='return confirm(\"Sigur vrei să ștergi acest utilizator?\");'>Șterge</button>
                            </form>
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
$conn = null;
?>
