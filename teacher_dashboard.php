<?php
// teacher_dashboard.php
session_start();
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
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold text-center mb-8">Teacher Timetable</h1>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="table-auto w-full border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="px-4 py-2">Day</th>
                        <th class="px-4 py-2">Time Slot</th>
                        <th class="px-4 py-2">Semester</th>
                        <th class="px-4 py-2">Batch</th>
                        <th class="px-4 py-2">Subject</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($timetable)) : ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">No timetable available.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($timetable as $row) : ?>
                            <tr class="border-b">
                                <td class="px-4 py-2 text-center"><?php echo htmlspecialchars($row['day']); ?></td>
                                <td class="px-4 py-2 text-center">
                                    <?php
                                    // Convert time to 12-hour format
                                    $start_time = date("h:i A", strtotime($row['time_slot']));
                                    $end_time = date("h:i A", strtotime($row['time_slot_end']));
                                    echo $start_time . " - " . $end_time;
                                    ?>
                                </td>
                                <td class="px-4 py-2 text-center"><?php echo htmlspecialchars($row['semester']); ?></td>
                                <td class="px-4 py-2 text-center"><?php echo htmlspecialchars($row['batch']); ?></td>
                                <td class="px-4 py-2 text-center"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-8 text-center">
            <a href="logout.php" class="text-blue-500 underline">Logout</a>
        </div>
    </div>
</body>

</html>
