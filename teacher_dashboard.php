<?php
// teacher_dashboard.php
session_start();
// include 'navigation.php';
include 'db_connection.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch timetable data for the logged-in teacher
$sql = "
    SELECT 
        tt.day,
        tt.time_slot,
        tt.time_slot_end,
        tt.semester,
        st.batch,
        sub.name AS subject_name,
        t.first_name AS teacher_name
    FROM timetable tt
    JOIN students st ON tt.batch_id = st.id
    JOIN subjects sub ON tt.subject_id = sub.id
    JOIN teachers t ON tt.teacher_id = t.id
    WHERE tt.teacher_id = ?
    ORDER BY 
        FIELD(tt.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
        tt.time_slot
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$timetable = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Timetable</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-blue-100 to-purple-100 min-h-screen">
    <div class="container mx-auto py-10 px-4">
        <h1 class="text-4xl font-bold text-center mb-8 text-gray-800 opacity-0" id="pageTitle">Teacher Timetable</h1>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden opacity-0" id="timetableCard">
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                            <th class="px-4 py-3 text-left">Day</th>
                            <th class="px-4 py-3 text-left">Time Slot</th>
                            <th class="px-4 py-3 text-left">Semester</th>
                            <th class="px-4 py-3 text-left">Batch</th>
                            <th class="px-4 py-3 text-left">Subject</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($timetable)) : ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">No timetable available.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($timetable as $index => $row) : ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-300 opacity-0" id="row-<?php echo $index; ?>">
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($row['day']); ?></td>
                                    <td class="px-4 py-3">
                                        <?php
                                        $start_time = date("h:i A", strtotime($row['time_slot']));
                                        $end_time = date("h:i A", strtotime($row['time_slot_end']));
                                        echo $start_time . " - " . $end_time;
                                        ?>
                                    </td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($row['semester']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($row['batch']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 text-center opacity-0" id="logoutButton">
            <a href="teacher_logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                Logout
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.to("#pageTitle", { opacity: 1, y: 0, duration: 1, ease: "power3.out" });
            gsap.to("#timetableCard", { opacity: 1, y: 0, duration: 1, delay: 0.5, ease: "power3.out" });
            gsap.to("#logoutButton", { opacity: 1, y: 0, duration: 1, delay: 1, ease: "power3.out" });

            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                gsap.to(row, { 
                    opacity: 1, 
                    y: 0, 
                    duration: 0.5, 
                    delay: 1 + (index * 0.1), 
                    ease: "power3.out" 
                });
            });
        });
    </script>
</body>

</html>

