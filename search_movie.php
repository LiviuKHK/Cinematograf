<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'includes/db_connection.php';

$conn = db_connect(); 

if (isset($_POST['movie'])) {
    $movie_name = htmlspecialchars($_POST['movie']); 

    $api_url = "http://www.omdbapi.com/?s=" . urlencode($movie_name) . "&apikey=c6a0d3a";

    $response = file_get_contents($api_url);
    $data = json_decode($response, true);

    if (isset($data['Search']) && count($data['Search']) > 0) {
        echo "<h2>Rezultatele căutării pentru: " . htmlspecialchars($movie_name) . "</h2>";

        foreach ($data['Search'] as $movie) {
            echo "<p><strong>" . htmlspecialchars($movie['Title']) . "</strong> (" . htmlspecialchars($movie['Year']) . ")</p>";
            echo "<p><img src='" . htmlspecialchars($movie['Poster']) . "' alt='" . htmlspecialchars($movie['Title']) . "' style='width: 100px;'></p>";

            $stmt = $conn->prepare("SELECT * FROM filme WHERE titlu = :titlu");
            $stmt->execute([':titlu' => $movie['Title']]);
            $film = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($film) {
                echo "<p><strong>Disponibil în cinema!</strong></p>";
            } else {
                echo "<p><strong>Nu este disponibil în cinema.</strong></p>";
            }

            echo "<hr>";
        }
    } else {
        echo "<p>Nu am găsit niciun film cu acest nume în baza de date OMDB.</p>";
    }
} else {
    echo "<p>Te rugăm să cauți un film folosind formularul de pe panoul de utilizator.</p>";
}

?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Căutare Film</title>
    <link rel="stylesheet" href="css/styles.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>Căutare Film</h1>
    <p><a href="user_dashboard.php">Înapoi la Dashboard</a></p>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
