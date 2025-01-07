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

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        $delete_id = htmlspecialchars($_POST['delete_id']);

        if ($delete_id != $_SESSION['user_id']) {
            $stmt = $conn->prepare("DELETE FROM utilizatori WHERE id = :id");
            $stmt->bindParam(':id', $delete_id);
            if ($stmt->execute()) {
                echo "Utilizator șters cu succes!";
            } else {
                echo "A apărut o eroare la ștergerea utilizatorului.";
            }
        } else {
            echo "Nu poți să te ștergi pe tine însuți!";
        }
    } else {
        die("Eroare CSRF - token invalid.");
    }
}

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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn = null;
?>
