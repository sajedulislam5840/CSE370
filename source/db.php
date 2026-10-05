<?php
session_start();
$conn = new mysqli("localhost", "root", "", "SparkEnergies");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>