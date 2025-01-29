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
$batches_query = "SELECT id, CONCAT('C', id) AS batch_name FROM students limit 3";
$subjects_query = "SELECT id, name, type FROM subjects";
$teachers_query = "SELECT id, CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) AS teacher_name FROM teachers";

$batches = $conn->query($batches_query);
$subjects = $conn->query($subjects_query);
$teachers = $conn->query($teachers_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $batch_id = isset($_POST['batch_id']) ? intval($_POST['batch_id']) : null;
    $semester = $_POST['semester'];
    $day = $_POST['day'];
    $time_slot = $_POST['time_slot'];
    $subject_id = intval($_POST['subject_id']);
    $teacher_id = intval($_POST['teacher_id']);
    $time_slot_end = $_POST['time_slot_end'];

    // Validate inputs
    if (!$subject_id || !$teacher_id || empty($semester) || empty($day) || empty($time_slot) || empty($time_slot_end)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        // Check for overlapping entries
        $overlap_query = "SELECT * FROM timetable 
                          WHERE id != ? AND day = ? AND (
                            (time_slot <= ? AND time_slot_end > ?) OR 
                            (time_slot < ? AND time_slot_end >= ?) OR 
                            (time_slot >= ? AND time_slot < ?)
                          ) AND batch_id = ? AND semester = ?";
        $stmt = $conn->prepare($overlap_query);
        $stmt->bind_param("isssssissi", $id, $day, $time_slot, $time_slot, $time_slot_end, $time_slot_end, $time_slot, $time_slot_end, $batch_id, $semester);
        $stmt->execute();
        $overlap_result = $stmt->get_result();
        $stmt->close();

        if ($overlap_result->num_rows > 0) {
            echo "<script>alert('Conflict detected with another class. Please choose a different time slot.');</script>";
        } else {
            // Update query
            $update_query = "UPDATE timetable 
                SET batch_id = ?, semester = ?, day = ?, time_slot = ?, time_slot_end = ?, subject_id = ?, teacher_id = ?
                WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("isssssii", $batch_id, $semester, $day, $time_slot, $time_slot_end, $subject_id, $teacher_id, $id);

            if ($stmt->execute()) {
                echo "<script>alert('Timetable updated successfully!'); window.location='admin_manage_timetable.php';</script>";
            } else {
                echo "<script>alert('Error updating timetable.');</script>";
            }
            $stmt->close();
        }
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
    <script>
        function toggleLabFields() {
            const subjectDropdown = document.getElementById('subject_id');
            const selectedOption = subjectDropdown.options[subjectDropdown.selectedIndex];
            const subjectType = selectedOption.dataset.type;

            const labFields = document.getElementById('lab-fields');
            if (subjectType === 'Lab') {
                labFields.style.display = 'block';
            } else {
                labFields.style.display = 'none';
            }
        }
    </script>
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
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        <option value="CO1k" <?php echo $timetable['semester'] === 'CO1k' ? 'selected' : ''; ?>>CO1k</option>
                        <option value="CO2k" <?php echo $timetable['semester'] === 'CO2k' ? 'selected' : ''; ?>>CO2k</option>
                        <option value="CO3k" <?php echo $timetable['semester'] === 'CO3k' ? 'selected' : ''; ?>>CO3k</option>
                        <option value="CO4k" <?php echo $timetable['semester'] === 'CO4k' ? 'selected' : ''; ?>>CO4k</option>
                        <option value="CO5k" <?php echo $timetable['semester'] === 'CO5k' ? 'selected' : ''; ?>>CO5k</option>
                        <option value="CO6k" <?php echo $timetable['semester'] === 'CO6k' ? 'selected' : ''; ?>>CO6k</option>
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
                        <option value="Wednesday" <?php echo $timetable['day'] === 'Wednesday' ? 'selected' : ''; ?>>Wednesday</option>
                        <option value="Thursday" <?php echo $timetable['day'] === 'Thursday' ? 'selected' : ''; ?>>Thursday</option>
                        <option value="Friday" <?php echo $timetable['day'] === 'Friday' ? 'selected' : ''; ?>>Friday</option>
                        <option value="Saturday" <?php echo $timetable['day'] === 'Saturday' ? 'selected' : ''; ?>>Saturday</option>
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
                    <select id="subject_id" name="subject_id" onchange="toggleLabFields()"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="">Select Subject</option>
                        <?php while ($subject = $subjects->fetch_assoc()) { ?>
                            <option value="<?php echo $subject['id']; ?>" data-type="<?php echo $subject['type']; ?>"
                                <?php echo $subject['id'] == $timetable['subject_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subject['name'] . " (" . $subject['type'] . ")"); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Lab fields (hidden by default) -->
                <div id="lab-fields" class="mb-4" style="display: none;">
                    <label for="batch_id" class="block text-gray-700 font-bold mb-2">Batch:</label>
                    <select id="batch_id" name="batch_id"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Batch</option>
                        <?php while ($batch = $batches->fetch_assoc()) { ?>
                            <option value="<?php echo $batch['id']; ?>" <?php echo $batch['id'] == $timetable['batch_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($batch['batch_name']); ?>
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
