<?php
include 'connection.php';

$sql = "SELECT first_name, last_name, membership_type, total_cost
        FROM members
        ORDER BY id DESC
        LIMIT 10";

$result = $conn->query($sql);

$rows = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    $rows = array_reverse($rows);

    // Output data of each row
    foreach ($rows as $row) {
        $membershipType = htmlspecialchars($row['membership_type']);
        $color = ''; // Default color
        switch ($membershipType) {
            case 'Daily Basic':
                $color = 'green';
                break;
            case 'Daily Pro':
                $color = 'darkorange';
                break;
            case 'Monthly Basic':
                $color = 'violet';
                break;
            case 'Monthly Pro':
                $color = 'blue';
                break;
            default:
                $color = 'black';
                break;
        }

        // Format total_cost to two decimal places
        $totalCost = number_format((float)$row['total_cost'], 2, '.', '');

        // Output row
        echo "<tr>";
        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['membership_type']) . "</td>";
        echo "<td class='border px-4 py-2 flex items-center'>
                <img src='../Assets/default_profile_image.png' style='margin-right:10px;' class='w-8 h-8 inline-block rounded-full' alt='Profile Image'>
                " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "
              </td>";
        echo "<td class='border px-4 py-2'>
                <div class='flex items-center'>
                  <img src='../Assets/pesos.png' style='width: 20px; height: 20px;' class='mr-2' alt='Money Icon'>
                  <span style='color: $color;'>" . htmlspecialchars($totalCost) . "</span>
                </div>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3' class='border px-4 py-2 text-center'>No recent customers found</td></tr>";
}

?>