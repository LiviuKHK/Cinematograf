<?php
include 'includes/db_connection.php';
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = $_POST['nume'];
    $email = $_POST['email'];
    $parola = $_POST['parola'];
    $rol = 'user';  

    if (!empty($nume) && !empty($email) && !empty($parola)) {
        $conn = db_connect();

        $stmt = $conn->prepare("SELECT * FROM utilizatori WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Acest email este deja utilizat!";
        } else {
            $stmt = $conn->prepare("INSERT INTO utilizatori (nume, email, parola, rol) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nume, $email, $parola, $rol);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            header("Location: login.php");
            exit;
        }
    } else {
        $error_message = "Toate câmpurile sunt obligatorii!";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/styles.css"> 
</head>
<body>
    <h1>Înregistrare Utilizator</h1>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="POST" action="signup.php">
        <label for="nume">Nume:</label>
        <input type="text" id="nume" name="nume" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="parola">Parolă:</label>
        <input type="password" id="parola" name="parola" required><br>

        <button type="submit">Înregistrează-te</button>
    </form>

    <p>Ai deja un cont? <a href="login.php">Autentifică-te aici</a></p>
</body>
</html>
