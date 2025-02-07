<?php
require_once 'db_connection.php'; // Replace with your database connection file
require_once 'navigation.php';
// Handle delete operations
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = intval($_GET['delete']);
    $type = $_GET['type'];

    if ($type == 'teacher') {
        $delete_query = "DELETE FROM teachers WHERE id = ?";
    } elseif ($type == 'subject') {
        $delete_query = "DELETE FROM subjects WHERE id = ?";
    }

    if (isset($delete_query)) {
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Deleted successfully!'); window.location='admin_manage.php';</script>";
    }
}

// Fetch teachers and subjects
$teachers_query = "SELECT * FROM teachers";
$teachers_result = $conn->query($teachers_query);

$subjects_query = "SELECT * FROM subjects";
$subjects_result = $conn->query($subjects_query);

// fetch data from timetable table
$timetable_query = "
    SELECT 
        timetable.*, 
        subjects.name AS subject_name, 
        subjects.type AS subject_type, 
        CONCAT(teachers.first_name, ' ', teachers.last_name) AS teacher_name 
    FROM timetable
    LEFT JOIN subjects ON timetable.subject_id = subjects.id
    LEFT JOIN teachers ON timetable.teacher_id = teachers.id
";

$timetable_result = $conn->query($timetable_query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Teachers and Subjects</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- GSAP for Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #f6f8f9 0%, #e5ebee 100%);
        }
    </style>
</head>

<body>
    <div class="flex items-center justify-center space-x-4 mb-8">

        <a href="admin_timetable.php"
            class=" w-62 text-center flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300 ease-in-out">
            <span class="animate-pulse">Semester wise timetable</span>
        </a>
        <a href="admin_dashboard.php"
            class=" w-62 text-center flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300 ease-in-out">
            <span class="animate-pulse">Dashboard</span>
        </a>

        <a href="index.php">
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-110 home">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Home
            </button>
        </a>
    </div>
    <div class="container mx-auto p-4 lg:p-8">
        <!-- Teachers Section -->
        <div class="mb-8">
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-blue-800">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>Teachers
                    </h2>
                   <a href="admin_dashboard.php"> <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">
                        Add Teacher
                    </button></a>
                </div>
                
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">First Name</th>
                                <th scope="col" class="px-6 py-3">Middle Name</th>
                                <th scope="col" class="px-6 py-3">Last Name</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($teachers_result && $teachers_result->num_rows > 0):
                                while ($teacher = $teachers_result->fetch_assoc()):
                            ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($teacher['id']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($teacher['first_name']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($teacher['middle_name']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($teacher['last_name']); ?></td>
                                    <td class="px-6 py-4">
                                        <a href="edit_teacher.php?id=<?php echo $teacher['id']; ?>" 
                                           class="font-medium text-blue-600 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            <?php
                                endwhile;
                            else:
                                echo "<tr><td colspan='5' class='px-6 py-4 text-center'>No teachers found</td></tr>";
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Subjects Section -->
        <div class="mb-8">
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-green-800">
                        <i class="fas fa-book mr-2"></i>Subjects
                    </h2>
                    <a href="admin_dashboard.php"> <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">
                        Add Subject
                    </button></a>
                </div>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($subjects_result && $subjects_result->num_rows > 0):
                                while ($subject = $subjects_result->fetch_assoc()):
                            ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($subject['id']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($subject['name']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            <?php echo htmlspecialchars($subject['type']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="edit_subject.php?id=<?php echo $subject['id']; ?>" 
                                           class="font-medium text-blue-600 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            <?php
                                endwhile;
                            else:
                                echo "<tr><td colspan='4' class='px-6 py-4 text-center'>No subjects found</td></tr>";
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Timetable Section -->
        <div>
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-purple-800">
                        <i class="fas fa-calendar-alt mr-2"></i>Timetable
                    </h2>
                    <a href="admin_dashboard.php"> <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">
                        Add Timetable Entry
                    </button></a>
                </div>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Branch</th>
                                <th scope="col" class="px-6 py-3">Batch</th>
                                <th scope="col" class="px-6 py-3">Semester</th>
                                <th scope="col" class="px-6 py-3">Day</th>
                                <th scope="col" class="px-6 py-3">Start Time</th>
                                <th scope="col" class="px-6 py-3">End Time</th>
                                <th scope="col" class="px-6 py-3">Subject</th>
                                <th scope="col" class="px-6 py-3">Teacher</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($timetable_result && $timetable_result->num_rows > 0):
                                while ($row = $timetable_result->fetch_assoc()):
                            ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            <?php echo htmlspecialchars($row['branch']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"><?php
                                        $batch_id = $row['batch_id'];
                                        echo !empty($batch_id) ? 'C' . htmlspecialchars($batch_id) : '';
                                    ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['semester']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['day']); ?></td>
                                    <td class="px-6 py-4"><?php echo date('g:i A', strtotime($row['time_slot'])); ?></td>
                                    <td class="px-6 py-4"><?php echo date('g:i A', strtotime($row['time_slot_end'])); ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-medium"><?php echo htmlspecialchars($row['subject_name']); ?></span>
                                            <span class="text-xs text-gray-500"><?php echo htmlspecialchars($row['subject_type']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                                    <td class="px-6 py-4">
                                        <form action="delete_timetable.php" method="POST" class="inline">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" 
                                                    class="font-medium text-red-600 hover:underline"
                                                    onclick="return confirm('Are you sure you want to delete this entry?');">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                                endwhile;
                            else:
                                echo "<tr><td colspan='10' class='px-6 py-4 text-center'>No timetable entries found</td></tr>";
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>



    <script>
        // GSAP Animations
        gsap.from(".bg-white", {
            opacity: 0,
            y: 50,
            stagger: 0.2,
            duration: 0.8,
            ease: "power2.out"
        });
    </script>
</body>

</html>