<?php
session_start();  

$loggedIn = isset($_SESSION['user_id']);
$userRole = isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
?>
<header>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/scripts.js"></script>
    <h1>Aplicația Cinematograf</h1>
    <nav>
        <ul>
            <li><a href="index.php">Acasă</a></li>
            <li><a href="about.php">Despre</a></li>
            
            <?php if ($loggedIn): ?>
                <li><a href="filme.php">Filme</a></li>
                <li><a href="rezervari.php">Rezervări</a></li>
                <?php if ($userRole === 'admin'): ?>
                    <li><a href="admin_dashboard.php">Dashboard Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Ieși din cont</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            <?php endif; ?>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <h2>Bine ați venit la Cinematograf!</h2>
    <p>Descoperiți cele mai recente filme, rezervați bilete și verificați programările. Totul este la un click distanță!</p>
</div>
