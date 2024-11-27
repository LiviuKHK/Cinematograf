<?php
session_start();
include 'includes/db_connection.php';

$conn = db_connect();
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $parola = $_POST['parola'];

    if (!empty($email) && !empty($parola)) {
        $stmt = $conn->prepare("SELECT id, rol FROM utilizatori WHERE email = ? AND parola = ?");
        $stmt->bind_param("ss", $email, $parola); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['rol'] = $user['rol'];  

            if ($user['rol'] === 'admin') {
                header("Location: admin_dashboard.php");  
                exit;
            } else {
                header("Location: user_dashboard.php");  
                exit;
            }
        } else {
            $error_message = "Email sau parolă greșită!";
        }
        $stmt->close();
    } else {
        $error_message = "Toate câmpurile sunt obligatorii!";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css"> 
</head>
<body>
    <h1>Autentificare</h1>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>
    
    <label for="parola">Parolă:</label>
    <input type="password" id="parola" name="parola" required><br>
    
    <button type="submit">Autentificare</button>
</form>


    <p>Nu ai un cont? <a href="signup.php">Înregistrează-te aici</a></p>
</body>
</html>
