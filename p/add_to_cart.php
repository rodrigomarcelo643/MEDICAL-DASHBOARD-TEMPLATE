<?php
header('Content-Type: application/json');

include 'connection.php';
// Extract POST data
$member_id = $_POST['member_id'];
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$total = $_POST['total'];
$member_name = $_POST['member_name'];

// Check stock availability
$sql = "SELECT Stocks FROM AddProducts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$stmt->bind_result($stock);
$stmt->fetch();
$stmt->close();

if ($stock === null) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    $conn->close();
    exit;
}

if ($stock < $quantity) {
    echo json_encode(['status' => 'error', 'message' => 'Insufficient stock']);
    $conn->close();
    exit;
}

$sql = "INSERT INTO cart_items (member_id, product_id, product_name, quantity, price, total, member_name) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssssss', $member_id, $product_id, $product_name, $quantity, $price, $total, $member_name);

if ($stmt->execute()) {
    $sql = "UPDATE AddProducts SET Stocks = Stocks - ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $quantity, $product_id);
    $stmt->execute();

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add item to cart']);
}

$stmt->close(); 
$conn->close();
?>