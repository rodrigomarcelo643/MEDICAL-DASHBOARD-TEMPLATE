<?php

$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "doctor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $lastName = $_POST['LastName'];
    $firstName = $_POST['FirstName'];
    $middleName = $_POST['MiddleName'];
    $specialty = $_POST['specialty'];
    $contactNumber = $_POST['contactNumber'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $licenseNumber = $_POST['licenseNumber'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("INSERT INTO doctors (last_name, first_name, middle_name, specialty, contact_number, email, dob, license_number, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $lastName, $firstName, $middleName, $specialty, $contactNumber, $email, $dob, $licenseNumber, $address);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
