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
    $lab_allocation = isset($_POST['lab_allocation']) ? $_POST['lab_allocation'] : NULL;

    // Prepare statements to fetch batch_id, subject_id, and teacher_id
    $batch_query = "SELECT id FROM students WHERE batch = ? LIMIT 1";
    $subject_query = "SELECT id, type FROM subjects WHERE name = ?";
    $teacher_query = "SELECT id FROM teachers WHERE CONCAT(first_name, ' ', last_name) = ?";

    // Fetch batch_id
    $stmt = $conn->prepare($batch_query);
    $stmt->bind_param("s", $batch);
    $stmt->execute();
    $stmt->bind_result($batch_id);
    $stmt->fetch();
    $stmt->close();

    // Fetch subject_id and type
    $stmt = $conn->prepare($subject_query);
    $stmt->bind_param("s", $subject);
    $stmt->execute();
    $stmt->bind_result($subject_id, $subject_type);
    $stmt->fetch();
    $stmt->close();

    // Fetch teacher_id
    $stmt = $conn->prepare($teacher_query);
    $stmt->bind_param("s", $teacher);
    $stmt->execute();
    $stmt->bind_result($teacher_id);
    $stmt->fetch();
    $stmt->close();

    $start_time_converted = date("H:i:s", strtotime($start_time));
    $end_time_converted = date("H:i:s", strtotime($end_time));



    $overlap_query = "
    SELECT id 
    FROM timetable 
    WHERE 
        day = ? 
        AND (
            (time_slot < ? AND time_slot_end > ?) OR 
            (time_slot < ? AND time_slot_end > ?) OR 
            (time_slot >= ? AND time_slot_end <= ?)
        )
        AND (
            teacher_id = ? OR 
            (batch_id = ? AND semester = ?) OR 
            (lab_allocation = ? AND lab_allocation IS NOT NULL)
        )
";

    $stmt = $conn->prepare($overlap_query);
    $stmt->bind_param(
        "sssssssiiis",
        $day,
        $end_time_converted,
        $start_time_converted,
        $start_time_converted,
        $end_time_converted,
        $start_time_converted,
        $end_time_converted,
        $teacher_id,
        $batch_id,
        $semester,
        $lab_allocation
    );
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // If an overlap exists, stop the process and show an error
        echo "<script>alert('Overlap detected! Please check the teacher, batch, or lab allocation for this time slot.');</script>";
        echo "<script>window.history.back();</script>";
        $stmt->close();
        $conn->close();
        exit;
    }

    $stmt->close();




    // Insert into timetable
    $insert_query = "INSERT INTO timetable (batch_id, semester, day, time_slot, time_slot_end, subject_id, teacher_id, lab_allocation, batch) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("issssisss", $batch_id, $semester, $day, $start_time_converted, $end_time_converted, $subject_id, $teacher_id, $lab_allocation, $batch);

    if ($stmt->execute()) {
        echo "<script>alert('Timetable generated successfully');</script>";
        echo "<script>window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to generate timetable. Check for overlapping resources.');</script>";
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>