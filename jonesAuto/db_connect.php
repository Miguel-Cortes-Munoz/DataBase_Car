<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lethbridge_jones_auto_db";
# Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully!";
?>