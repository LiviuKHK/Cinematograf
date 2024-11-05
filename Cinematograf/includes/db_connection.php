<?php
function db_connect() {
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "Cinematograf"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        return false; 
    }
    return $conn; 
}
?>
