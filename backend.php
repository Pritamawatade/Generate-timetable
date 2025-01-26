<?php
// Include the database connection
require_once 'db_connection.php'; // Adjust the path if needed

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

    // Check if there are results
    if ($result->num_rows > 0) {
        echo "<table border='1' width='100%' style='text-align: center; border-collapse: collapse;'>";
        echo "<tr><th>Day</th><th>Time Slot</th><th>Subject</th><th>Teacher Initials</th></tr>";

        // Output data for each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['day']) . "</td>";
            echo "<td>" . htmlspecialchars($row['time_slot']) . " - " . htmlspecialchars($row['time_slot_end']) . "</td>";
            echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['teacher_initials']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No timetable data found for the selected batch and semester.</p>";
    }

    $stmt->close();
} else {
    echo "<p>Invalid request method.</p>";
}

$conn->close();
?>
