<?php
header('Content-Type: application/json'); 

include 'connection.php';

$stmt = $conn->prepare("INSERT INTO members (first_name, last_name, contact_number, membership_type, membership_start, membership_due_date, total_cost) VALUES (?, ?, ?, ?, ?, ?, ?)");

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$contactNumber = $_POST['contactNumber'];
$membershipType = $_POST['membershipType'];
$membershipStart = date('Y-m-d');
$membershipDueDate = calculateDueDate($membershipStart, $membershipType);
$totalCost = $_POST['totalCost'];

$stmt->bind_param("sssssss", $firstName, $lastName, $contactNumber, $membershipType, $membershipStart, $membershipDueDate, $totalCost);

if ($stmt->execute()) {
    $newMember = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'contact_number' => $contactNumber,
        'membership_type' => $membershipType,
        'membership_start' => $membershipStart,
        'membership_due_date' => $membershipDueDate,
        'total_cost' => $totalCost,
    ];
    echo json_encode($newMember);
} else {
    echo json_encode(['error' => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();

function calculateDueDate($startDate, $type) {
    $startDate = new DateTime($startDate);

    switch ($type) {
        case 'daily-basic':
        case 'daily-pro':
            $dueDate = $startDate->add(new DateInterval('P1D'));
            break;
        case 'monthly-basic':
        case 'monthly-pro':
            $dueDate = $startDate->add(new DateInterval('P1M'));
            break;
        default:
            $dueDate = $startDate;
            break;
    }
    return $dueDate->format('Y-m-d');
}
?>