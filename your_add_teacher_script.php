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

    // Execute the query to insert teacher
    if ($conn->query($query)) {
        // Get the last inserted teacher ID
        $new_teacher_id = $conn->insert_id;

        // Generate the password for the teacher
        $password = "teacher" . $new_teacher_id;

        // Insert into the teacher_login table
        $login_query = "INSERT INTO teachers_login (teacher_id, password) VALUES ('$new_teacher_id', '$password')";

        // Execute the login query
        if ($conn->query($login_query)) {
            echo "<script>alert('Teacher added successfully');</script>";
            echo "<script>window.location.href='admin_dashboard.php';</script>";
        } else {
            // If failed to insert login details
            echo "<script>alert('Failed to add login details');</script>";
            echo "Error: " . $conn->error;
            

            // echo "<script>window.location.href='admin_dashboard.php';</script>";
        }
    } else {
        // If failed to insert teacher data
        echo "<script>alert('Failed to add teacher');</script>";
        echo "<script>window.location.href='admin_dashboard.php';</script>";
    }
}

// Close the database connection
$conn->close();
?>
