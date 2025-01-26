<?php
// Include the database connection file
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get batch and semester from the request
    $batch = $_POST['batch'];
    $semester = $_POST['semester'];

    // Prepare the query to fetch timetable data
    $sql = "
        SELECT 
            tt.day,
            tt.time_slot,
            tt.time_slot_end,
            sub.name AS subject_name,
            CONCAT(UPPER(LEFT(t.first_name, 1)), UPPER(LEFT(t.middle_name, 1)), UPPER(LEFT(t.last_name, 1))) AS teacher_initials
        FROM timetable tt
        JOIN students st ON tt.batch_id = st.id
        JOIN subjects sub ON tt.subject_id = sub.id
        JOIN teachers t ON tt.teacher_id = t.id
        WHERE st.batch = ? AND tt.semester = ?
        ORDER BY 
            FIELD(tt.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
            tt.time_slot
    ";

    // Execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $batch, $semester);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize the timetable matrix
    $timetable = [
        "Monday" => [],
        "Tuesday" => [],
        "Wednesday" => [],
        "Thursday" => [],
        "Friday" => [],
        "Saturday" => []
    ];

    // Populate the timetable matrix with data
    while ($row = $result->fetch_assoc()) {
        // $timeSlot = date("h:i A", strtotime($row['time_slot'])) . " - " . date("h:i A", strtotime($row['time_slot_end']));
        // $timetable[$row['day']][$timeSlot] = $row['subject_name'] . " [" . $row['teacher_initials'] . "]";
        echo "
        <table style="/width:100%; height:70%; border:1px solid black; font-size:10px; line-height:1.5; table-layout:fixed;/"  >
        <tr>
                <td></td>
                <td>10:45-11:45</td>
                <td>10:45-11:45</td>
                <td>10:45-11:45</td>
                <td>10:45-11:45</td>
                <td>10:45-11:45</td>
                <td>10:45-11:45</td>
                <td>10:45-11:45</td>
            </tr>
            <tr>
                <td>" . $row['day'] . "</td>
                <td>" . $row['subject_name'] . " [" . $row['teacher_initials'] . "]</td>
                <td>" . $row['subject_name'] . " [" . $row['teacher_initials'] . "]</td>
                <td>" . $row['subject_name'] . " [" . $row['teacher_initials'] . "]</td>
                <td>" . $row['subject_name'] . " [" . $row['teacher_initials'] . "]</td>
                <td>" . $row['subject_name'] . " [" . $row['teacher_initials'] . "]</td>
                <td>" . $row['subject_name'] . " [" . $row['teacher_initials'] . "]</td>
                <td>" . $row['subject_name'] . " [" . $row['teacher_initials'] . "]</td>
            </tr>
        </table>
    ";
    
    }

    // Close the statement and connection
    // $stmt->close();
    // $conn->close();

    // // Return the timetable as a JSON response
    // header('Content-Type: application/json');
    // echo json_encode($timetable);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

</head>

<body>

</body>

</html>