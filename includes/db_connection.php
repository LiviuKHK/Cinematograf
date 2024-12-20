<?php
function db_connect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Cinematograf";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $conn;
    } catch (PDOException $e) {
        die("Eroare la conectarea la baza de date: " . $e->getMessage());
    }
}
?>
