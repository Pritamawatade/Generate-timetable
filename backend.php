<?php
// Include dompdf library
require 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Connect to the database
$conn = new mysqli("localhost", "root", "", "timetable");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include Tailwind and GSAP CDN links
$cdn_links = '
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester = $_POST['semester'];
    $branch = $_POST['branch'];

    // SQL Query
    $sql = "
        SELECT 
            tt.day,
            tt.time_slot,
            tt.time_slot_end,
            tt.batch,
            tt.lab_allocation,
            sub.name AS subject_name,
            sub.type AS subject_type,
            CONCAT(t.first_name, ' ', t.last_name) AS teacher_name
        FROM timetable tt
        JOIN subjects sub ON tt.subject_id = sub.id
        JOIN teachers t ON tt.teacher_id = t.id
        WHERE tt.semester = ? AND tt.branch = ?
        ORDER BY 
            FIELD(tt.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
            TIME(tt.time_slot)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $semester, $branch);
    $stmt->execute();
    $result = $stmt->get_result();

    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $time_slots = [];
    $timetable = [];

    // Process the query results
    while ($row = $result->fetch_assoc()) {
        $start_time = date("h:i A", strtotime($row['time_slot']));
        $end_time = date("h:i A", strtotime($row['time_slot_end']));
        $time_range = "$start_time - $end_time";

        if (!in_array($time_range, $time_slots)) {
            $time_slots[] = $time_range;
        }

        if ($row['subject_type'] === 'LAB') {
            $timetable[$row['day']][$time_range][] = "{$row['batch']} → {$row['subject_name']} {$row['lab_allocation']} ({$row['teacher_name']})";
        } else {
            $timetable[$row['day']][$time_range][] = "{$row['subject_name']} ({$row['teacher_name']})";
        }
    }

    // Sort time slots
    usort($time_slots, function ($a, $b) {
        $a_start = strtotime(explode(' - ', $a)[0]);
        $b_start = strtotime(explode(' - ', $b)[0]);
        return $a_start - $b_start;
    });

    // Generate the HTML for the timetable
    $html = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Timetable - $branch Semester $semester</title>
        $cdn_links
        <style>
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid black; padding: 8px; text-align: center; }
        </style>
    </head>
    <body class='bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen p-8'>
        <div class='max-w-6xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden' id='timetable1'>
            <div class='bg-blue-600 text-white p-6 text-center'>
                <h1 class='text-4xl font-bold mb-4'>Government Residential Women's Polytechnic Latur</h1>

                <h2 class='text-3xl font-bold tracking-tight animate-pulse'>
                    Timetable for Branch: $branch, Semester: $semester
                </h2>
            </div>
            <div class='p-6 overflow-x-auto'>
                <table class='w-full text-sm text-left text-gray-600 border-collapse' id='timetableAnimation'>
                    <thead class='bg-blue-100 text-blue-800'>
                        <tr>
                            <th class='p-4 font-bold border'>Day</th>";

    foreach ($time_slots as $time_range) {
        $html .= "<th class='p-4 font-bold border'>$time_range</th>";
    }

    $html .= "</tr></thead><tbody>";

    foreach ($days as $day) {
        $html .= "<tr class='hover:bg-blue-50 transition-colors'><td class='p-4 font-semibold border'>$day</td>";
        foreach ($time_slots as $time_range) {
            $cellContent = isset($timetable[$day][$time_range])
                ? implode("<br>", $timetable[$day][$time_range])
                : "<span class='text-gray-400'>-</span>";
            $html .= "<td class='p-4 border'>$cellContent</td>";
        }
        $html .= "</tr>";
    }

    $html .= "</tbody></table>
            </div>
            <div class='p-6 text-center'>
                <form method='post' class='inline-block'>
                    <input type='hidden' name='semester' value='$semester'>
                    <input type='hidden' name='branch' value='$branch'>
                    <button type='submit' name='generate_pdf' class='bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full'>
                        Download PDF
                    </button>
                </form>
            </div>
             
        </div>
          <a href='index.php'>
            <button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-110 home'>
                <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 19l-7-7m0 0l7-7m-7 7h18'></path></svg>
                Back to Home
            </button>
        </a>
    </body>
    </html>";

    // Handle PDF download
    if (isset($_POST['generate_pdf'])) {
        ob_clean(); 
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Only include the div with id 'timetable1'
        $dompdf->stream("timetable_branch{$branch}_semester{$semester}.pdf", ["Attachment" => true]);
        exit;
    }

    // Display timetable on the webpage
    echo $html;
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
