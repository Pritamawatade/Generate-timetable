<?php
// Include database connection
require_once 'db_connection.php';

// Fetch distinct branches from the timetable table
$branches_query = "SELECT DISTINCT branch FROM timetable";
$branches_result = $conn->query($branches_query);

// Get selected branch and semester from the GET request
$selected_branch = isset($_GET['branch']) ? $_GET['branch'] : '';
$selected_semester = isset($_GET['semester']) ? $_GET['semester'] : '';

// Fetch semesters based on the selected branch
$semesters_query = "SELECT DISTINCT semester FROM timetable WHERE branch = ?";
$semesters_stmt = $conn->prepare($semesters_query);
$semesters_stmt->bind_param("s", $selected_branch);
$semesters_stmt->execute();
$semesters_result = $semesters_stmt->get_result();

// Fetch timetable based on selected branch and semester
$timetable_result = null;
if ($selected_semester && $selected_branch) {
    $timetable_query = "SELECT t.*, s.name AS subject_name, CONCAT(teacher.first_name, ' ', teacher.last_name) AS teacher_name 
                        FROM timetable t
                        JOIN subjects s ON t.subject_id = s.id
                        JOIN teachers teacher ON t.teacher_id = teacher.id
                        WHERE t.semester = ? AND t.branch = ?
                        ORDER BY t.day, t.time_slot";
    $stmt = $conn->prepare($timetable_query);
    $stmt->bind_param("ss", $selected_semester, $selected_branch);
    $stmt->execute();
    $timetable_result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Timetable View</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body class="bg-gray-100">
    <div class="container py-6">
        <h1 class="text-3xl font-bold text-center mb-6">Semester-Wise Timetable</h1>

        <a href="admin_dashboard.php" class="mb-6">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-110">
                Admin Dashboard
            </button>
        </a>

        <!-- Semester Selection Form -->
        <form method="GET" class="mb-6 mt-6">
            <div class="flex gap-4 justify-center">
                <!-- Branch Dropdown -->
                <select name="branch" class="form-select w-1/3 p-2 border rounded-lg" required onchange="this.form.submit()">
                    <option value="">Select Branch</option>
                    <?php while ($row = $branches_result->fetch_assoc()): ?>
                        <option value="<?= $row['branch'] ?>" <?= $row['branch'] == $selected_branch ? 'selected' : '' ?>>
                            <?= $row['branch'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- Semester Dropdown -->
                <select name="semester" class="form-select w-1/3 p-2 border rounded-lg" required>
                    <option value="">Select Semester</option>
                    <?php while ($row = $semesters_result->fetch_assoc()): ?>
                        <option value="<?= $row['semester'] ?>" <?= $row['semester'] == $selected_semester ? 'selected' : '' ?>>
                            <?= $row['semester'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" class="btn btn-primary">View Timetable</button>
            </div>
        </form>

        <!-- Timetable Display -->
        <?php if ($timetable_result && $timetable_result->num_rows > 0): ?>
            <div class="table-responsive bg-white shadow-lg rounded-lg p-4">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Day</th>
                            <th>Time Slot</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Batch</th>
                            <th>Lab Allocation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $timetable_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['day'] ?></td>
                                <td><?= date("h:i A", strtotime($row['time_slot'])) ?> - <?= date("h:i A", strtotime($row['time_slot_end'])) ?></td>
                                <td><?= $row['subject_name'] ?></td>
                                <td><?= $row['teacher_name'] ?></td>
                                <td><?= $row['batch'] ?></td>
                                <td><?= $row['lab_allocation'] ?: 'N/A' ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($selected_semester): ?>
            <div class="text-center text-gray-600 mt-6">
                <p>No timetable available for the selected semester.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
