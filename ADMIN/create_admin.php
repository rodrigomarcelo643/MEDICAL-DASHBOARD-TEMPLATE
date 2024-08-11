<?php

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "admin"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Get POST data
    $admin_username = $conn->real_escape_string($_POST['username']);
    $admin_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Insert into database
    $sql = "INSERT INTO admin_credentials (username, password) VALUES ('$admin_username', '$admin_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Admin account created successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
