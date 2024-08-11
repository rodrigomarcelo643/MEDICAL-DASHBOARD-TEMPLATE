<?php
    $server = "localhost";
    $username = "root";
    $passsword = "";
    $db="db_admin";

    $conn  = new mysqli($server, $username, $passsword, $db);

    if ($conn->connect_error) {
        die("Connection error ". $conn->connect_error);
    }

    if($_SERVER['REQUEST_METHOD'] == "POST"){

       
    }
?>