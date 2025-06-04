<?php

try {
    
    echo "✅ Database connection file loaded successfully!<br>";
    

    if ($conn->connect_error) {
        echo "❌ Connection failed: " . $conn->connect_error . "<br>";
    } else {
        echo "✅ Connected to database successfully!<br>";
        echo "Database: " . $conn->get_server_info() . "<br>";
        

        $result = $conn->query("SELECT COUNT(*) as count FROM employees");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "✅ Can access employees table. Current count: " . $row['count'] . "<br>";
        } else {
            echo "❌ Cannot access employees table: " . $conn->error . "<br>";
        }
        
 
        $tables = ['employee_profile', 'employee_employment_information', 'emergency_contacts'];
        foreach ($tables as $table) {
            $result = $conn->query("SELECT COUNT(*) as count FROM $table");
            if ($result) {
                $row = $result->fetch_assoc();
                echo "✅ Can access $table table. Current count: " . $row['count'] . "<br>";
            } else {
                echo "❌ Cannot access $table table: " . $conn->error . "<br>";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error loading database connection: " . $e->getMessage() . "<br>";
    echo "Current directory: " . __DIR__ . "<br>";
    echo "Looking for: db_connection.php<br>";
    
    // List files in current directory
    echo "<br>Files in current directory:<br>";
    $files = scandir('.');
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- $file<br>";
        }
    }
}
?>