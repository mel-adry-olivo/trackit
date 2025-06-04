<?php
session_start();
require '../database/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = $_POST['task'];
    $timeline = $_POST['timeline'];
    $status = $_POST['status'];
    $date = $_POST['date'];
    $priority = $_POST['priority'];

    // Insert task into the database
    $task_sql = "INSERT INTO tasks (task, timeline, status, date, priority) 
                 VALUES ('$task', '$timeline', '$status', '$date', '$priority')";

    if ($conn->query($task_sql) === TRUE) {
        // Check if product details are provided
        if (!empty($_POST['productName']) && !empty($_POST['productPrice']) && !empty($_POST['productQty'])) {
            $productName = $_POST['productName'];
            $productPrice = $_POST['productPrice'];
            $productQty = $_POST['productQty'];

            // Insert product into the database
            $product_sql = "INSERT INTO products (productName, productPrice, productQty, productImg) 
                            VALUES ('$productName', '$productPrice', '$productQty', '')";

            $conn->query($product_sql);
        }

        // Debugging: Check if the dashboard file exists before redirecting
        if (!file_exists("../actions/dashboard.php")) {
            die("Error: dashboard.php not found in actions folder!");
        }

        // Redirect to the correct dashboard location
        header("Location: ../actions/dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
