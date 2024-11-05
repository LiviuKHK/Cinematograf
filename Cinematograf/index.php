<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinematograf - Acasă</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>Aplicația Cinematograf</h1>
    <nav>
        <ul>
            <li><a href="index.php">Acasă</a></li>
            <li><a href="about.php">Despre</a></li>
            <li><a href="filme.php">Filme</a></li>
            <li><a href="rezervari.php">Rezervări</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <h2>Bine ați venit la Cinematograf!</h2>
    <p>Descoperiți cele mai recente filme, rezervați bilete și verificați programările. Totul este la un click distanță!</p>

    <?php
    include 'includes/db_connection.php';
    $conn = db_connect();

    if ($conn) {
        echo "<p>Conexiunea la baza de date a fost realizată cu succes!</p>";
    } else {
        echo "<p>Eroare la conectare.</p>";
    }

    $conn->close();
    ?>

    <section>
        <h3>Filmele disponibile</h3>
        <p>Vezi cele mai noi filme disponibile pentru vizionare.</p>
    </section>

    <section>
        <h3>Programări</h3>
        <p>Verifică programările filmelor și alege-ți filmul preferat!</p>
    </section>
</div>

<footer>
    <p>&copy; 2024 Cinematograf. Toate drepturile rezervate.</p>
    <div class="copyright"></div>
</footer>



<script src="js/scripts.js"></script>
</body>
</html>
