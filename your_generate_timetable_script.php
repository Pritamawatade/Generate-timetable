<?php
// Include database connection
require_once 'db_connection.php'; // Replace with your actual database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form inputs
    $branch = $_POST['branch'];
    $semester = $_POST['semester'];
    $day = $_POST['day'];
    $start_time = $_POST['time_slot'];
    $end_time = $_POST['time_slot_end'];
    $subject = $_POST['subject'];
    $teacher = $_POST['teacher'];
    $lab_allocation = isset($_POST['lab_allocation']) ? $_POST['lab_allocation'] : null;
    $batch = isset($_POST['batch']) ? $_POST['batch'] : null;

    // Convert time to MySQL-compatible format
    $start_time_converted = date("H:i:s", strtotime($start_time));
    $end_time_converted = date("H:i:s", strtotime($end_time));

    // Check if start time is in the future compared to end time
    if ($start_time >= $end_time) {
        echo "<script>alert('Start time must be earlier than end time.'); window.history.back();</script>";
        exit;
    }


    
    // Fetch teacher ID
    $teacher_query = "SELECT id FROM teachers WHERE id = ?";
    $stmt = $conn->prepare($teacher_query);
    $stmt->bind_param("s", $teacher);
    $stmt->execute();
    $stmt->bind_result($teacher_id);
    $stmt->fetch();
    $stmt->close();

    // Fetch subject ID and type
    $subject_query = "SELECT id, type FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($subject_query);
    $stmt->bind_param("i", $subject);
    $stmt->execute();
    $stmt->bind_result($subject_id, $subject_type);
    $stmt->fetch();
    $stmt->close();

    // Fetch batch ID if batch is provided
    $batch_id = null;
    if (!empty($batch)) {
        $batch_query = "SELECT id FROM students WHERE batch = ? AND semester = ? LIMIT 1";
        $stmt = $conn->prepare($batch_query);
        $stmt->bind_param("ss", $batch, $semester);
        $stmt->execute();
        $stmt->bind_result($batch_id);
        $stmt->fetch();
        $stmt->close();
    }
    $overlap_query = "
    SELECT id FROM timetable
    WHERE 
        day = ? 
        AND (
            (time_slot < ? AND time_slot_end > ?) OR  -- Case 1: New slot starts inside an existing slot
            (time_slot < ? AND time_slot_end > ?) OR  -- Case 2: New slot ends inside an existing slot
            (time_slot >= ? AND time_slot_end <= ?) OR -- Case 3: New slot is fully within an existing slot
            (time_slot <= ? AND time_slot_end >= ?)  -- Case 4: Existing slot fully contains the new slot
        ) 
        AND (
            teacher_id = ? OR 
            batch_id = ? OR 
            lab_allocation = ? 
            AND (semester = ?)
        ) 
        
        
";

    // Prepare the statement
    $stmt = $conn->prepare($overlap_query);

    // Bind parameters
    $stmt->bind_param(
        "sssssssssiiss",
        $day,
        $end_time_converted,
        $start_time_converted,
        $start_time_converted,
        $end_time_converted,
        $start_time_converted,
        $end_time_converted,
        $start_time_converted,
        $end_time_converted,
        $teacher_id,  // ✅ Check if the teacher is already scheduled
        $batch_id,  // ✅ Check if the batch is already scheduled
        $lab_allocation,
        $semester

    );

    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($conflict_id);
        while ($stmt->fetch()) {
            echo "<script>alert('Conflict detected with another class. Conflict ID: $conflict_id. Teacher or class is busy. Please choose a different time slot.'); window.history.back();</script>";
        }
        exit;
    }
    $stmt->close();


    // ✅ **Insert Data if No Overlap**
    $insert_query = "
        INSERT INTO timetable 
            (batch_id, semester, day, time_slot, time_slot_end, subject_id, teacher_id, lab_allocation, batch, branch) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insert_query);

    if ($subject_type === 'LAB') {
        // LAB subjects require batch and lab_allocation
        $stmt->bind_param(
            "issssissss",
            $batch_id,
            $semester,
            $day,
            $start_time_converted,
            $end_time_converted,
            $subject_id,
            $teacher_id,
            $lab_allocation,
            $batch,
            $branch
        );
    } else {
        $n = "";
        // Theory subjects do not require batch and lab_allocation
        $stmt->bind_param(
            "issssissss",
            $batch_id,
            $semester,
            $day,
            $start_time_converted,
            $end_time_converted,
            $subject_id,
            $teacher_id,
            $n,
            $n,
            $branch
        );
    }

    // Execute the query and handle the result
    if ($stmt->execute()) {
        echo "<script>alert('Timetable generated successfully');</script>";
        echo "<script>window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to generate timetable. Please check your inputs.');</script>";
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
