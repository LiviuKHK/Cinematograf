<?php
include 'send_email.php';
$error_message = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = $_POST['nume'];
    $email = $_POST['email'];
    $mesaj = $_POST['mesaj'];

    if (!empty($nume) && !empty($email) && !empty($mesaj)) {
        $subiect = "Mesaj de la $nume ($email)";
        $body = "<p><strong>Nume:</strong> $nume</p><p><strong>Email:</strong> $email</p><p><strong>Mesaj:</strong> $mesaj</p>";

        $result = send_email('adresa_adminului@gmail.com', $subiect, $body);

        $success_message = $result;
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Contact</title>
</head>
<body>
    <h1>Contact</h1>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <form method="POST" action="contact.php">
        <label for="nume">Nume:</label>
        <input type="text" id="nume" name="nume" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="mesaj">Mesaj:</label>
        <textarea id="mesaj" name="mesaj" required></textarea><br>

        <button type="submit">Trimite</button>
    </form>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
