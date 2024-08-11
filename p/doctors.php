<?php
$servename = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "doctor";

$conn = new mysqli($servename, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function highlightText($text, $term) {
    if (empty($term)) {
        return htmlspecialchars($text); 
    }
    return preg_replace('/(' . preg_quote($term, '/') . ')/iu', '<mark>$1</mark>', htmlspecialchars($text));
}


if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $searchTerm = $conn->real_escape_string($query);

    $sql = "SELECT * FROM doctors WHERE 
            last_name LIKE '%$searchTerm%' OR 
            first_name LIKE '%$searchTerm%' OR 
            specialty LIKE '%$searchTerm%' OR 
            contact_number LIKE '%$searchTerm%' OR 
            email LIKE '%$searchTerm%'";

    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . highlightText($row["last_name"], $query) . "</td>";
                echo "<td>" . highlightText($row["first_name"], $query) . "</td>";
                echo "<td>" . highlightText($row["specialty"], $query) . "</td>";
                echo "<td>" . highlightText($row["contact_number"], $query) . "</td>";
                echo "<td>" . highlightText($row["email"], $query) . "</td>";
                echo "<td>";
                echo "<a href='edit.php?id=" . htmlspecialchars($row["id"]) . "' class='edit-link'>Edit</a> | ";
                echo "<a href='delete.php?id=" . htmlspecialchars($row["id"]) . "' class='delete-link'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='no-records'>No records found</td></tr>";
        }
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
    exit();
}

$sql = "SELECT * FROM doctors";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["specialty"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["contact_number"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td>";
            echo "<a href='edit.php?id=" . htmlspecialchars($row["id"]) . "' class='edit-link'>Edit</a> | ";
            echo "<a href='delete.php?id=" . htmlspecialchars($row["id"]) . "' class='delete-link'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7' class='no-records'>No records found</td></tr>";
    }
} else {
    echo "Error: " . $conn->error;
}


?>
