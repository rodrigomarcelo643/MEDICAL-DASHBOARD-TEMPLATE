<?php
include 'connection.php';

$sql = "SELECT * FROM AddProducts";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<h2>" . htmlspecialchars($row['ProductName']) . "</h2>";
        echo "<p>Price: " . htmlspecialchars($row['Price']) . "</p>";
        echo "<p>Type: " . htmlspecialchars($row['ProductType']) . "</p>";
        echo "</div>";
    }
} else {
    echo "No products found";
}


?>