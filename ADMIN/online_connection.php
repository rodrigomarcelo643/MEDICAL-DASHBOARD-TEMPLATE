<?php
// Use the provided Hostinger MySQL server hostname
$servername = "srv1367.hstgr.io";  // or use "153.92.15.25"
$username = "u843230181_Yokoks";   // Your MySQL username
$password = "Rodrigo#12345";       // Your MySQL password
$dbname = "u843230181_YokoksGym";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>