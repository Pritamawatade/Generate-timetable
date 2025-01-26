<?php
// Include database connection
include 'db_connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $middle_name = $conn->real_escape_string($_POST['middle_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);

    // Insert data into the teachers table
    $query = "INSERT INTO teachers (first_name, middle_name, last_name) 
              VALUES ('$first_name', '$middle_name', '$last_name')";

    // Execute the query
    if ($conn->query($query)) {
        echo "<script>alert('Teacher added successfully');</script>";
        echo "<script>window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to add teacher');</script>";
        echo "<script>window.location.href='admin_dashboard.php';</script>";
    }
}

// Close the database connection
$conn->close();
?>
