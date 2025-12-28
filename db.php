<?php
// Database configuration variables
// $host: The hostname of the database server (usually 'localhost' for local development)
$host = 'localhost';
// $dbname: The specific name of the database we are connecting to
$dbname = 'kvsp_library';
// $username: The database user (default 'root' for XAMPP)
$username = 'root';
// $password: The password for the database user (default empty for XAMPP)
$password = '';

try {
    // Attempt to create a new PDO connection instance
    // PDO (PHP Data Objects) is a secure way to access databases in PHP
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set the PDO error mode to Exception
    // This ensures that any database errors will throw an exception that we can catch
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If the connection fails, catch the exception and display the error message
    // Then stop the script execution
    echo "Connection failed: " . $e->getMessage();
    die();
}
