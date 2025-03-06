<?php
$host = "localhost";
$user = "root";
$password = ""; //CHANGE THIS TO A STRONG PASSWORD!
$database = "gudang_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    $error_msg = "Connection failed: " . $conn->connect_error;
    error_log($error_msg); // Log the error
    die($error_msg);      // Then display a more general message to the user
}

// Check if the database selected is actually the one you intended
if ($conn->select_db($database) === FALSE) {
  $error_msg = "Error selecting database: " . $conn->error;
  error_log($error_msg);
  die("Database selection failed.");
}

//Add more error handling as needed
?>
