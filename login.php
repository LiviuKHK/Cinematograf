<?php
session_start();
require_once 'includes/db_connection.php'; 

$conn = db_connect();
$error_message = "";
$user_ip = $_SERVER['REMOTE_ADDR']; 

$max_attempts = 5; 
$lockout_time = 900; 

$recaptcha_secret_key = '6Ld7xqEqAAAAAL7xqNcbGdfvbw2mmqcsdrnljTXz';  
$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $parola = $_POST['parola'];
    $recaptcha_response = $_POST['g-recaptcha-response']; 

    if (!empty($email) && !empty($parola) && !empty($recaptcha_response)) {
     
        $response = file_get_contents($recaptcha_url . "?secret=" . $recaptcha_secret_key . "&response=" . $recaptcha_response);
        $response_keys = json_decode($response, true);
        
        if (intval($response_keys["success"]) !== 1) {
            $error_message = "Verificarea reCAPTCHA a eșuat. Te rugăm să încerci din nou.";
        } else {
            
            $stmt = $conn->prepare("SELECT attempts, last_attempt FROM login_attempts WHERE ip_address = :ip_address");
            $stmt->execute([':ip_address' => $user_ip]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $attempts = $row['attempts'];
                $last_attempt = strtotime($row['last_attempt']);

                if ($attempts >= $max_attempts && time() - $last_attempt < $lockout_time) {
                    $error_message = "Ai depășit numărul maxim de încercări. Te rugăm să aștepți 15 minute înainte de a încerca din nou.";
                }
            }

            if (empty($error_message)) {
               
                try {
                    $stmt = $conn->prepare("SELECT id, rol, parola FROM utilizatori WHERE email = :email");
                    $stmt->execute([':email' => $email]);

                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user && password_verify($parola, $user['parola'])) {
                        
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['rol'] = $user['rol'];

                        $stmt_reset = $conn->prepare("DELETE FROM login_attempts WHERE ip_address = :ip_address");
                        $stmt_reset->execute([':ip_address' => $user_ip]);

                        if ($user['rol'] === 'admin') {
                            header("Location: admin_dashboard.php");
                        } else {
                            header("Location: user_dashboard.php");
                        }
                        exit;
                    } else {
                       
                        $stmt_attempt = $conn->prepare("INSERT INTO login_attempts (ip_address, attempts, last_attempt) 
                                                        VALUES (:ip_address, 1, NOW()) 
                                                        ON DUPLICATE KEY UPDATE attempts = attempts + 1, last_attempt = NOW()");
                        $stmt_attempt->execute([':ip_address' => $user_ip]);

                        $error_message = "Email sau parolă greșită!";
                    }
                } catch (PDOException $e) {
                    $error_message = "Eroare la procesarea cererii. Te rugăm să încerci din nou.";
                }
            }
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
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css"> 
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>Autentificare</h1>

    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="parola">Parolă:</label>
        <input type="password" id="parola" name="parola" required><br>

        <div class="g-recaptcha" data-sitekey="6Ld7xqEqAAAAAO0FnQV9Zn-twbQ_Boluqe5xf4Gg"></div> 

        <button type="submit">Autentificare</button>
    </form>

    <p>Nu ai un cont? <a href="signup.php">Înregistrează-te aici</a></p>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
