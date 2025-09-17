<?php
$servername = "localhost";
$username = "root";
$password = "Kavindugimhan@334";  // Default XAMPP password is empty
$dbname = "liora_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    // Don't die() here as it outputs HTML, let the calling script handle it
    $db_connection_error = "Connection failed: " . $conn->connect_error;
}

// Set charset
$conn->set_charset("utf8");

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}