<?php

    date_default_timezone_set('Asia/Manila');
    $server = "localhost";
    $username = "root";
    $password = "";
    $db_name ="gym";

    $conn = new mysqli($server, $username , $password, $db_name);

    if($conn -> connect_error){
        die("Connection fatal and Disconnetected" . $conn->connect_error);
    }
?>