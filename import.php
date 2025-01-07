<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];

    if ($file['type'] === 'text/csv' || pathinfo($file['name'], PATHINFO_EXTENSION) === 'csv') {
        if (($handle = fopen($file['tmp_name'], 'r')) !== FALSE) {
            include 'includes/db_connection.php';
            $conn = db_connect();

            fgetcsv($handle);

            try {
                $stmt = $conn->prepare("INSERT INTO utilizatori (nume, email, parola, rol) VALUES (?, ?, ?, ?)");

                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $stmt->bindParam(1, $data[0]);
                    $stmt->bindParam(2, $data[1]);
                    $stmt->bindParam(3, password_hash($data[2], PASSWORD_BCRYPT)); 
                    $stmt->bindParam(4, $data[3]);
                    $stmt->execute();
                }
                fclose($handle);
                echo "Datele au fost importate cu succes!";
            } catch (PDOException $e) {
                echo "Eroare la import: " . $e->getMessage();
            }
        }
    } else {
        echo "Te rugăm să încarci un fișier CSV valid.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Import Utilizatori</title>
</head>
<body>
    <h1>Importă Utilizatori din CSV</h1>
    <form action="import.php" method="POST" enctype="multipart/form-data">
        <label for="csv_file">Alege fișierul CSV:</label>
        <input type="file" name="csv_file" id="csv_file" required>
        <button type="submit">Importă</button>
    </form>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
