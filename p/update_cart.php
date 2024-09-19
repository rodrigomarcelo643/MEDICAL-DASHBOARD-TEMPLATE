<?php
// Database connection parameters
$host = 'localhost'; // Adjust as necessary
$dbname = 'gym'; // Adjust as necessary
$username = 'root'; // Adjust as necessary
$password = ''; // Adjust as necessary

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Fetch POST data
$data = $_POST;
$memberId = isset($data['member_id']) ? intval($data['member_id']) : 0;
$itemsJson = isset($data['items']) ? $data['items'] : '[]';

// Decode the JSON array
$items = json_decode($itemsJson, true);

if (!is_array($items)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid items data']);
    exit;
}

try {
    // Begin a transaction
    $pdo->beginTransaction();

    // Prepare the SQL statement
    $stmt = $pdo->prepare("
        INSERT INTO cart_items (member_id, product_id, product_name, quantity, price, total)
        VALUES (:member_id, :product_id, :product_name, :quantity, :price, :total)
        ON DUPLICATE KEY UPDATE
            quantity = VALUES(quantity),
            price = VALUES(price),
            total = VALUES(total)
    ");

    // Execute the prepared statement for each item
    foreach ($items as $item) {
        $stmt->execute([
            ':member_id' => $memberId,
            ':product_id' => intval($item['product_id']),
            ':product_name' => $item['product_name'],
            ':quantity' => intval($item['quantity']),
            ':price' => floatval($item['price']),
            ':total' => floatval($item['total']),
        ]);
    }

    // Commit the transaction
    $pdo->commit();

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    // Rollback the transaction on error
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Failed to update cart: ' . $e->getMessage()]);
}
?>