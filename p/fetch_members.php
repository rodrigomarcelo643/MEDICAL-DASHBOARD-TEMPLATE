<?php

include 'connection.php';

$sql = "SELECT * FROM members";
$result = $conn->query($sql);

$members = [];

$currentDate = new DateTime();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dueDate = new DateTime($row['membership_due_date']);
        
    
        $interval = $currentDate->diff($dueDate);
        $daysRemaining = $interval->days;
        $hoursRemaining = $daysRemaining * 24 + $interval->h;
        $minutesRemaining = $hoursRemaining * 60 + $interval->i;
        $secondsRemaining = $minutesRemaining * 60 + $interval->s;

        if ($currentDate > $dueDate) {
            $remainingTime = "<span class='expired-status'> Expired</span>";
        } else {
            if ($secondsRemaining >= 604800) { 
                $weeksRemaining = floor($secondsRemaining / 604800);
                $remainingTime = "<span class='active-status-green'>" . $weeksRemaining . " weeks remaining</span>";
            } elseif ($secondsRemaining >= 86400) { 
                $daysRemaining = floor($secondsRemaining / 86400);
                $remainingTime = "<span class='active-status'> " . $daysRemaining . " days remaining</span>";
            } elseif ($secondsRemaining >= 3600) {
                $hoursRemaining = floor($secondsRemaining / 3600);
                $remainingTime = "<span class='active-status'>" . $hoursRemaining . " hours remaining</span>";
            } elseif ($secondsRemaining >= 60) {
                $minutesRemaining = floor($secondsRemaining / 60);
                $remainingTime = "<span class='active-status'> " . $minutesRemaining . " minutes remaining</span>";
            } else { 
                $remainingTime = "<span class='active-status'> " . $secondsRemaining . " seconds remaining</span>";
            }
        }

        
        $members[] = [
            'id' => $row['id'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'contact_number' => $row['contact_number'],
            'membership_type' => $row['membership_type'],
            'total_cost' => $row['total_cost'],
            'remaining_time' => $remainingTime
        ];
    }
}

$conn->close();



?>