<?php
// Include database connection
require_once 'db_connection.php'; // Replace with your actual database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form inputs
    $batch = $_POST['batch'];
    $semester = $_POST['semester'];
    $day = $_POST['day'];
    $start_time = $_POST['time_slot'];
    $subject = $_POST['subject'];
    $teacher = $_POST['teacher'];
    $end_time = $_POST['time_slot_end'];

    // Prepare statements to fetch batch_id, subject_id, and teacher_id
    $batch_query = "SELECT id FROM students WHERE batch = ?";
    $subject_query = "SELECT id FROM subjects WHERE name = ?";
    $teacher_query = "SELECT id FROM teachers WHERE CONCAT(first_name, ' ', last_name) = ?";

    // Fetch batch_id
    $stmt = $conn->prepare($batch_query);
    $stmt->bind_param("s", $batch);
    $stmt->execute();
    $stmt->bind_result($batch_id);
    $stmt->fetch();
    $stmt->close();

    // Fetch subject_id
    $stmt = $conn->prepare($subject_query);
    $stmt->bind_param("s", $subject);
    $stmt->execute();
    $stmt->bind_result($subject_id);
    $stmt->fetch();
    $stmt->close();

    // Fetch teacher_id
    $stmt = $conn->prepare($teacher_query);
    $stmt->bind_param("s", $teacher);
    $stmt->execute();
    $stmt->bind_result($teacher_id);
    $stmt->fetch();
    $stmt->close();

    // list($start_time, $end_time) = explode(" - ", $time_slot);

    // // Convert both times to TIME format
    // $start_time_converted = date("H:i:s", strtotime($start_time));
    // $end_time_converted = date("H:i:s", strtotime($end_time));

    // // Combine the start and end times into a single string
    // $time_slot_combined = $start_time_converted . ' - ' . $end_time_converted;

    // // Now you can store the combined time slot in the database
    // // Insert into timetable
    // $insert_query = "INSERT INTO timetable (batch_id, semester, day, time_slot, subject_id, teacher_id) 
    //                  VALUES (?, ?, ?, ?, ?, ?)";
    // $stmt = $conn->prepare($insert_query);
    // $stmt->bind_param("isssii", $batch_id, $semester, $day, $time_slot_converted, $subject_id, $teacher_id);




    // // Extract the start and end times from the time slot
    // list($start_time, $end_time) = explode(" - ", $time_slot);

    // // Convert both times to TIME format
    $start_time_converted = date("H:i:s", strtotime($start_time));
    $end_time_converted = date("H:i:s", strtotime($end_time));

    // Now you can store the start and end times in the database
    $insert_query = "INSERT INTO timetable (batch_id, semester, day, time_slot, time_slot_end, subject_id, teacher_id) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("issssii", $batch_id, $semester, $day, $start_time_converted, $end_time_converted, $subject_id, $teacher_id);


    if ($stmt->execute()) {
        echo "<script>alert('Timetable generated successfully');</script>";
        echo "<script>window.location.href='admin_dashboard.php';</script>";
    } else {

        // echo "<script>alert('Failed to generate timetable. Resources might be unavailable 
        // ');</script>";

        echo 'Error: ' . $stmt->error;
        // echo "<script>window.location.href='admin_dashboard.php';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>