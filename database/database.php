<?php


function connect_to_database()
{
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "taskmanagementdb";

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

?>