<?php
$host = "localhost"; // Adjust if needed
$username = "root"; // Adjust if needed
$password = ""; // Adjust if needed
$database = "user";

$con = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
