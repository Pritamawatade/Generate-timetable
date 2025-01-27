<?php
// Include dompdf library from the folder you pasted
require 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
// include 'navigation.php';

// Connect to the database
$conn = new mysqli("localhost", "root", "", "timetable");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cdn_links = '
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch = $_POST['batch'];
    $semester = $_POST['semester'];

    $sql = "
        SELECT 
            tt.day,
            tt.time_slot,
            tt.time_slot_end,
            tt.lab_allocation,
            st.batch,
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

        $timetable[$row['day']][$time_range] = "{$row['subject_name']} ({$row['lab_allocation']}) [{$row['teacher_initials']}]";
    }

    usort($time_slots, function($a, $b) {
        $a_start = strtotime(explode(' - ', $a)[0]);
        $b_start = strtotime(explode(' - ', $b)[0]);
        return $a_start - $b_start;
    });

    // Generate HTML timetable
    $html = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Timetable - $batch Semester $semester</title>
        $cdn_links
        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
          <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: center;
            }
        </style>
    </head>
    <body class='bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen p-8'>
        <div class='max-w-6xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden'>
            <div class='bg-blue-600 text-white p-6 text-center '>
                <h2 class='text-3xl font-bold tracking-tight animate-pulse'>
                    Timetable for Batch: $batch, Semester: $semester
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
                    ? "{$timetable[$day][$time_range]}" 
                    : "<span class='text-gray-400'>-</span>";
                $html .= "<td class='p-4 border'>$cellContent</td>";
            }
            $html .= "</tr>";
        }
    
        $html .= "</tbody></table>
            </div>
            
            <div class='p-6 text-center'>
                <form method='post' class='inline-block'>
                    <input type='hidden' name='batch' value='$batch'>
                    <input type='hidden' name='semester' value='$semester'>
                    <button type='submit' name='generate_pdf' class='bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full transition-all transform hover:scale-105 hover:shadow-lg'>
                        Download PDF
                    </button>
                </form>
            </div>
        </div>

            <div class='fixed bottom-10 right-10'>
                <a href='index.php'>
                    <button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-110 animate-bounce'>
                        <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 19l-7-7m0 0l7-7m-7 7h18'></path></svg>
                        Back to Home
                    </button>
                </a>
            </div>
        
    
        <script>
            // GSAP Animations
            gsap.registerPlugin(ScrollTrigger);
            
            // Fade in table rows
            gsap.from('#timetableAnimation tbody tr', {
                opacity: 0,
                y: 50,
                stagger: 0.1,
                duration: 0.5,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '#timetableAnimation',
                    start: 'top 80%'
                }
            });
    
            // Hover effects for table rows
            document.querySelectorAll('#timetableAnimation tbody tr').forEach(row => {
                row.addEventListener('mouseenter', () => {
                    gsap.to(row, {
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        scale: 1.02,
                        duration: 0.3
                    });
                });
                row.addEventListener('mouseleave', () => {
                    gsap.to(row, {
                        backgroundColor: 'transparent',
                        scale: 1,
                        duration: 0.3
                    });
                });
            });
        </script>
    </body>
    </html>
    ";

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
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
