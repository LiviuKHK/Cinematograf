<?php
session_start();

include 'includes/db_connection.php'; 

$conn = db_connect();

try {
    $stmt = $conn->prepare("SELECT COUNT(id) AS total_users FROM utilizatori");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_users = $result['total_users'];
    $stmt = null;
} catch (PDOException $e) {
    die("Eroare la obținerea numărului de utilizatori: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Grafic utilizatori</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="css/styles.css"> 
</head>
<body>
    <h1>Grafic utilizatori înregistrați</h1>

    <div>
        <canvas id="usersChart"></canvas>
    </div>

    <script>
        var totalUsers = <?php echo $total_users; ?>;

        var ctx = document.getElementById('usersChart').getContext('2d');
        var usersChart = new Chart(ctx, {
            type: 'bar', 
            data: {
                labels: ['Număr utilizatori'], 
                datasets: [{
                    label: 'Utilizatori Înregistrați',
                    data: [totalUsers], 
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', 
                    borderColor: 'rgba(54, 162, 235, 1)', 
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true 
                    }
                }
            }
        });
    </script>
<button><a href="admin_dashboard.php">Inapoi</a></button>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
