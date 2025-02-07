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
    $timetable_query = "SELECT t.*, s.name AS subject_name,s.type AS sub_type, CONCAT(teacher.first_name, ' ', teacher.last_name) AS teacher_name 
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
    <title>Timetable View</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>Semester Timetable
            </h1>
            <p class="text-gray-600">View and manage semester-wise class schedules</p>
        </div>

        <!-- Navigation Button -->
        <div class="mb-8">
            <a href="admin_dashboard.php" class="inline-flex items-center px-5 py-3 text-base font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <form method="GET" class="space-y-4 md:space-y-0 md:flex md:gap-4 md:items-end">
                <div class="flex-1">
                    <label for="branch" class="block mb-2 text-sm font-medium text-gray-900">Branch</label>
                    <select name="branch" id="branch" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required onchange="this.form.submit()">
                        <option value="">Select Branch</option>
                        <?php while ($row = $branches_result->fetch_assoc()): ?>
                            <option value="<?= $row['branch'] ?>" <?= $row['branch'] == $selected_branch ? 'selected' : '' ?>>
                                <?= $row['branch'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="flex-1">
                    <label for="semester" class="block mb-2 text-sm font-medium text-gray-900">Semester</label>
                    <select name="semester" id="semester" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <option value="">Select Semester</option>
                        <?php while ($row = $semesters_result->fetch_assoc()): ?>
                            <option value="<?= $row['semester'] ?>" <?= $row['semester'] == $selected_semester ? 'selected' : '' ?>>
                                Semester <?= $row['semester'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="w-full md:w-auto px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                    <i class="fas fa-search mr-2"></i>View Timetable
                </button>
            </form>
        </div>

        <!-- Timetable Section -->
        <?php if ($timetable_result && $timetable_result->num_rows > 0): ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Day</th>
                                <th scope="col" class="px-6 py-3">Time Slot</th>
                                <th scope="col" class="px-6 py-3">Subject</th>
                                <th scope="col" class="px-6 py-3">Teacher</th>
                                <th scope="col" class="px-6 py-3">Batch</th>
                                <th scope="col" class="px-6 py-3">Lab Allocation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $timetable_result->fetch_assoc()): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        <i class="fas fa-calendar-day text-blue-600 mr-2"></i>
                                        <?= $row['day'] ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <i class="fas fa-clock text-green-600 mr-2"></i>
                                        <?= date("h:i A", strtotime($row['time_slot'])) ?> - <?= date("h:i A", strtotime($row['time_slot_end'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            <?= $row['subject_name'] ?>
                                        </span>
                                        <span class="block text-gray-500 text-xs mt-1">
                                            <?= $row['sub_type'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <i class="fas fa-user-tie text-gray-600 mr-2"></i>
                                        <?= $row['teacher_name'] ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            <?= $row['batch'] ?: 'All Batches' ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($row['lab_allocation']): ?>
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                <?= $row['lab_allocation'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php elseif ($selected_semester): ?>
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
                <p class="text-gray-600">No timetable available for the selected semester.</p>
                <p class="text-sm text-gray-500 mt-2">Try selecting a different semester or branch.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>