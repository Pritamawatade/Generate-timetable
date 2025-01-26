<?php
// Include dompdf library from the folder you pasted
require 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Connect to the database
$conn = new mysqli("localhost", "root", "", "timetable");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch = $_POST['batch'];
    $semester = $_POST['semester'];

    $sql = "
        SELECT 
            tt.day,
            tt.time_slot,
            tt.time_slot_end,
            sub.name AS subject_name,
            sub.type AS subject_type,
            CONCAT(UPPER(LEFT(t.first_name, 1)), UPPER(LEFT(t.middle_name, 1)), UPPER(LEFT(t.last_name, 1))) AS teacher_initials
        FROM timetable tt
        JOIN students st ON tt.batch_id = st.id
        JOIN subjects sub ON tt.subject_id = sub.id
        JOIN teachers t ON tt.teacher_id = t.id
        WHERE st.batch = ? AND tt.semester = ?
        ORDER BY 
            FIELD(tt.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
            TIME(tt.time_slot)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $batch, $semester);
    $stmt->execute();
    $result = $stmt->get_result();

    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $time_slots = [];
    $timetable = [];

    while ($row = $result->fetch_assoc()) {
        $start_time = date("h:i A", strtotime($row['time_slot']));
        $end_time = date("h:i A", strtotime($row['time_slot_end']));
        $time_range = "$start_time - $end_time";

        if (!in_array($time_range, $time_slots)) {
            $time_slots[] = $time_range;
        }

        $timetable[$row['day']][$time_range] = "{$row['subject_name']} ({$row['subject_type']}) [{$row['teacher_initials']}]";
    }

    usort($time_slots, function($a, $b) {
        $a_start = strtotime(explode(' - ', $a)[0]);
        $b_start = strtotime(explode(' - ', $b)[0]);
        return $a_start - $b_start;
    });

    // Generate HTML timetable
    $html = "<h2 style='text-align: center;'>Timetable for Batch: $batch, Semester: $semester</h2>";
    $html .= "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; text-align: center;'>";
    $html .= "<tr><th>Day</th>";

    foreach ($time_slots as $time_range) {
        $html .= "<th>$time_range</th>";
    }
    $html .= "</tr>";

    foreach ($days as $day) {
        $html .= "<tr><td>$day</td>";
        foreach ($time_slots as $time_range) {
            if (isset($timetable[$day][$time_range])) {
                $html .= "<td>{$timetable[$day][$time_range]}</td>";
            } else {
                $html .= "<td>-</td>";
            }
        }
        $html .= "</tr>";
    }

    $html .= "</table>";

    // Check if PDF generation is requested
    if (isset($_POST['generate_pdf'])) {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("timetable_batch{$batch}_semester{$semester}.pdf", ["Attachment" => true]);
        exit;
    }

    // Display timetable on the webpage
    echo $html;

    // Display Download as PDF button
    echo "<form method='post'>
        <input type='hidden' name='batch' value='$batch'>
        <input type='hidden' name='semester' value='$semester'>
        <input type='submit' name='generate_pdf' value='Download as PDF' style='margin-top: 20px; padding: 10px 20px; font-size: 16px; cursor: pointer;'>
    </form>";
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
