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
$error_message = "";
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = $_POST['nume'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if (!empty($nume) && !empty($email) && !empty($role)) {
        $stmt = $conn->prepare("UPDATE utilizatori SET nume = :nume, email = :email, rol = :role WHERE id = :id");
        $stmt->bindParam(':nume', $nume, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Utilizator actualizat cu succes!";
        } else {
            $error_message = "A apărut o problemă la actualizarea utilizatorului!";
        }
    } else {
        $error_message = "Toate câmpurile sunt obligatorii!";
    }
}

$stmt = $conn->prepare("SELECT nume, email, rol FROM utilizatori WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $nume = htmlspecialchars($user['nume']);  
    $email = htmlspecialchars($user['email']);  
    $role = $user['rol'];
} else {
    echo "Utilizatorul nu a fost găsit!";
    exit;
}

$conn = null;
?>

<h1>Editare Utilizator</h1>
<?php if (!empty($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>
<form method="POST" action="edit_user.php?id=<?php echo $id; ?>">
    <label for="nume">Nume:</label>
    <input type="text" id="nume" name="nume" value="<?php echo $nume; ?>" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required><br>

    <label for="role">Rol:</label>
    <select id="role" name="role">
        <option value="user" <?php echo $role === 'user' ? 'selected' : ''; ?>>User</option>
        <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
    </select><br>

    <button type="submit">Salvează Modificările</button>
</form>
