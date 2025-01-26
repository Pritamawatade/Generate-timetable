<?php
// Include database connection
require_once 'db_connection.php';

// Fetch timetable entry based on ID from the URL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM timetable WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $timetable = $result->fetch_assoc();
    $stmt->close();

    // Redirect if timetable entry is not found
    if (!$timetable) {
        echo "<script>alert('Timetable entry not found!'); window.location='admin_manage_timetable.php';</script>";
        exit;
    }
}

// Fetch data for dropdowns (batches, subjects, teachers)
$batches_query = "SELECT id, CONCAT('Batch ', id) AS batch_name FROM students";
$subjects_query = "SELECT id, name FROM subjects";
$teachers_query = "SELECT id, CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) AS teacher_name FROM teachers";

$batches = $conn->query($batches_query);
$subjects = $conn->query($subjects_query);
$teachers = $conn->query($teachers_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $batch_id = intval($_POST['batch_id']);
    $semester = $_POST['semester'];
    $day = $_POST['day'];
    $time_slot = $_POST['time_slot'];
    $subject_id = intval($_POST['subject_id']);
    $teacher_id = intval($_POST['teacher_id']);
    $time_slot_end = $_POST['time_slot_end']; // End time

    // Validate inputs
    if (!$batch_id || !$subject_id || !$teacher_id || empty($semester) || empty($day) || empty($time_slot)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        // Update query
        $update_query = "UPDATE timetable 
            SET batch_id = ?, semester = ?, day = ?, time_slot = ?, time_slot_end = ?, subject_id = ?, teacher_id = ?
            WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("isssssii", $batch_id, $semester, $day, $time_slot, $time_slot_end, $subject_id, $teacher_id, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Timetable updated successfully!'); window.location='admin_manage.php';</script>";
        } else {
            echo "<script>alert('Error updating timetable. Please ensure no overlapping classes.');</script>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Timetable</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-4">Edit Timetable Entry</h1>
            <form method="POST" action="edit_timetable.php">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($timetable['id']); ?>">

                <div class="mb-4">
                    <label for="batch_id" class="block text-gray-700 font-bold mb-2">Batch:</label>
                    <select id="batch_id" name="batch_id"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="">Select Batch</option>
                        <?php while ($batch = $batches->fetch_assoc()) { ?>
                            <option value="<?php echo $batch['id']; ?>" <?php echo $batch['id'] == $timetable['batch_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($batch['batch_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="semester" class="block text-gray-700 font-bold mb-2">Semester:</label>
                    <select id="semester" name="semester"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="1st year" <?php echo $timetable['semester'] === '1st year' ? 'selected' : ''; ?>>1st year</option>
                        <option value="2nd year" <?php echo $timetable['semester'] === '2nd year' ? 'selected' : ''; ?>>2nd year</option>
                        <option value="3rd year" <?php echo $timetable['semester'] === '3rd year' ? 'selected' : ''; ?>>3rd year</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="day" class="block text-gray-700 font-bold mb-2">Day:</label>
                    <select id="day" name="day"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="Monday" <?php echo $timetable['day'] === 'Monday' ? 'selected' : ''; ?>>Monday
                        </option>
                        <option value="Tuesday" <?php echo $timetable['day'] === 'Tuesday' ? 'selected' : ''; ?>>Tuesday
                        </option>
                        <option value="Wednesday" <?php echo $timetable['day'] === 'Wednesday' ? 'selected' : ''; ?>>
                            Wednesday</option>
                        <option value="Thursday" <?php echo $timetable['day'] === 'Thursday' ? 'selected' : ''; ?>>
                            Thursday</option>
                        <option value="Friday" <?php echo $timetable['day'] === 'Friday' ? 'selected' : ''; ?>>Friday
                        </option>
                        <option value="Saturday" <?php echo $timetable['day'] === 'Saturday' ? 'selected' : ''; ?>>
                            Saturday</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="time_slot" class="block text-gray-700 font-bold mb-2">Start Time:</label>
                    <input type="time" id="time_slot" name="time_slot"
                        value="<?php echo htmlspecialchars($timetable['time_slot']); ?>"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="time_slot_end" class="block text-gray-700 font-bold mb-2">End Time:</label>
                    <input type="time" id="time_slot_end" name="time_slot_end"
                        value="<?php echo htmlspecialchars($timetable['time_slot_end']); ?>"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>


                <div class="mb-4">
                    <label for="subject_id" class="block text-gray-700 font-bold mb-2">Subject:</label>
                    <select id="subject_id" name="subject_id"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="">Select Subject</option>
                        <?php while ($subject = $subjects->fetch_assoc()) { ?>
                            <option value="<?php echo $subject['id']; ?>" <?php echo $subject['id'] == $timetable['subject_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subject['name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="teacher_id" class="block text-gray-700 font-bold mb-2">Teacher:</label>
                    <select id="teacher_id" name="teacher_id"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="">Select Teacher</option>
                        <?php while ($teacher = $teachers->fetch_assoc()) { ?>
                            <option value="<?php echo $teacher['id']; ?>" <?php echo $teacher['id'] == $timetable['teacher_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($teacher['teacher_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save
                        Changes</button>
                    <a href="admin_manage_timetable.php" class="text-gray-600 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>