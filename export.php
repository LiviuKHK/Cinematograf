<?php

include 'includes/db_connection.php';
$conn = db_connect();

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=utilizatori.csv');

$output = fopen('php://output', 'w');

fputcsv($output, ['ID', 'Nume', 'Email', 'Rol']);

try {
    $stmt = $conn->prepare("SELECT id, nume, email, rol FROM utilizatori");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
} catch (PDOException $e) {
    echo "Eroare la export: " . $e->getMessage();
}
?>
