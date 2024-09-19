<?php
// getProductsForMember.php
$memberId = $_GET['memberId'];
// Perform SQL query to get products based on memberId
$query = "SELECT ProductName, ProductId, Price, Stocks, image FROM AddProducts WHERE member_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$memberId]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
?>