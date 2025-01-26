<?php
// Include database connection
include 'db_connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $subject_name = $conn->real_escape_string($_POST['subject_name']);
    $subject_type = $conn->real_escape_string($_POST['subject_type']);

    // Insert data into the subjects table
    $query = "INSERT INTO subjects (name, type) VALUES ('$subject_name', '$subject_type')";

    if ($conn->query($query)) {
        echo "<script>alert('Subject added successfully');</script>";
        echo "<script>window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to add subject');</script>";
        echo "<script>window.location.href='admin_dashboard.php';</script>";
    }
}

// Close the database connection
$conn->close();
?>
