<?php
require '../vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include 'connection.php'; 

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch the data from your expenses table
$query = "SELECT date, description, amount, type, first_name, last_name FROM expenses"; // Adjusted columns
$result = $conn->query($query); // Use $conn instead of $mysqli

if (!$result) {
    die("Query failed: " . $conn->error); // Use $conn here as well
}

// Create a new Spreadsheet object
try {
    $spreadsheet = new Spreadsheet();
} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
    exit;
}

// Set the header row
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Date');
$sheet->setCellValue('B1', 'Description');
$sheet->setCellValue('C1', 'Amount');
$sheet->setCellValue('D1', 'Type');
$sheet->setCellValue('F1', 'First Name');
$sheet->setCellValue('G1', 'Last Name');

// Populate the sheet with data
$rowNum = 2; // Start from the second row
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['date']);
    $sheet->setCellValue('B' . $rowNum, $row['description']);
    $sheet->setCellValue('C' . $rowNum, $row['amount']);
    $sheet->setCellValue('D' . $rowNum, $row['type']);
    $sheet->setCellValue('F' . $rowNum, $row['first_name']);
    $sheet->setCellValue('G' . $rowNum, $row['last_name']);
    $rowNum++;
}

// Close the database connection
$conn->close(); // Use $conn here as well

// Set the headers to trigger the Excel download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: inline; filename="expenses.xls"'); // Change to inline for real-time display
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Pragma: public');
header('Expires: 0');

// Write the file to the output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;