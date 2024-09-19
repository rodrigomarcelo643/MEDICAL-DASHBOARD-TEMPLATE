<?php
include 'connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $amount = isset($_POST['amount']) ? trim($_POST['amount']) : '';
    
 
    if (empty($description) || !is_numeric($amount) || $amount <= 0) {
        echo "Invalid input data";
        exit();
    }

    $date = date('Y-m-d');

    $sql = "INSERT INTO expenses (description, amount, date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }


    $stmt->bind_param("sss", $description, $amount, $date);

    if ($stmt->execute()) {
        echo "Expense added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

}
?>