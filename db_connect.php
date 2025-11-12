<?php
$host = 'localhost';
$db_username = 'root';
$password = '';
$database = 'lifelinelocator';


$conn = new mysqli($host, $db_username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


