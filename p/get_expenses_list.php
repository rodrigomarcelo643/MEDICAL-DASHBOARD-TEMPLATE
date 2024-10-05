<?php
header('Content-Type: application/json');

// Include the database connection file
include 'connection.php'; // Ensure this file contains your database connection logic

// Get filter parameters from the request
$date = isset($_GET['date']) ? $_GET['date'] : '';
$staffName = isset($_GET['staffName']) ? $_GET['staffName'] : '';

// Build the SQL query with optional filters
$sql = "SELECT 
            id, 
            DATE_FORMAT(date, '%M %d, %Y') AS date, 
            image, 
            description, 
            type, 
            supplier, 
            amount, 
            CONCAT(first_name, ' ', last_name) AS full_name 
        FROM expenses
        WHERE 1=1"; // Default condition to allow appending filters

// Add exact date filter if provided
if (!empty($date)) {
    $sql .= " AND date = '$date'";
}

// Add staff full name filter if provided
if (!empty($staffName)) {
    $sql .= " AND CONCAT(first_name, ' ', last_name) LIKE '%$staffName%'";
}

$sql .= " ORDER BY date DESC"; // Order by date in descending order

$result = $conn->query($sql);

$expenses = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // If an image is present, encode it as base64
        if (!empty($row['image'])) {
            $imageData = base64_encode($row['image']);
            $row['image'] = 'data:image/jpeg;base64,' . $imageData;
        } else {
            $row['image'] = null;
        }
        $expenses[] = $row;
    }
    echo json_encode($expenses);
} else {
    echo json_encode(['error' => 'Failed to fetch expenses: ' . $conn->error]);
}

$conn->close();
?>