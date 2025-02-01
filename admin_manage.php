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
$timetable_query = "SELECT * FROM timetable";
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
    <style>
        body {
            background: linear-gradient(135deg, #f6f8f9 0%, #e5ebee 100%);
        }
    </style>
</head>
<body>
   <div class="flex items-center justify-center space-x-4 mb-8">
     
   <a href="admin_timetable.php" class=" w-62 text-center flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300 ease-in-out">
        <span class="animate-pulse">Semester wise timetable</span>
    </a>
    <a href="admin_dashboard.php" class=" w-62 text-center flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300 ease-in-out">
        <span class="animate-pulse">Dashboard</span>
    </a>

    <a href="index.php">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-110 home">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Home
            </button>
        </a>
</div>
    <div class="container mx-auto p-8">
        <?php
        // Reset result pointers
        // $conn->data_seek(0);
        $teachers_result = $conn->query($teachers_query);
        ?>
        <!-- Teachers Section -->
        <div class="bg-white shadow-2xl rounded-2xl p-8 mb-12">
            <h1 class="text-3xl font-bold mb-6 text-blue-800">Manage Teachers</h1>

            <table class="w-full">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="p-3">ID</th>
                        <th class="p-3">First Name</th>
                        <th class="p-3">Middle Name</th>
                        <th class="p-3">Last Name</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Ensure we have a valid result set
                    if ($teachers_result && $teachers_result->num_rows > 0):
                        while ($teacher = $teachers_result->fetch_assoc()): 
                    ?>
                    <tr class="border-b hover:bg-blue-50">
                        <td class="p-3"><?php echo htmlspecialchars($teacher['id']); ?></td>
                        <td class="p-3"><?php echo htmlspecialchars($teacher['first_name']); ?></td>
                        <td class="p-3"><?php echo htmlspecialchars($teacher['middle_name']); ?></td>
                        <td class="p-3"><?php echo htmlspecialchars($teacher['last_name']); ?></td>
                        <td class="p-3">
                            <a href="edit_teacher.php?id=<?php echo $teacher['id']; ?>" class="text-blue-500 mr-2">Edit</a>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                        echo "<tr><td colspan='5' class='text-center p-4'>No teachers found</td></tr>";
                    endif; 
                    ?>
                </tbody>
            </table>
        </div>

        <?php
        // Reset result pointers
        // $conn->data_seek(0);
        $subjects_result = $conn->query($subjects_query);
        ?>
        <!-- Subjects Section -->
        <div class="bg-white shadow-2xl rounded-2xl p-8 mb-12">
            <h1 class="text-3xl font-bold mb-6 text-green-800">Manage Subjects</h1>
            <table class="w-full">
                <thead>
                    <tr class="bg-green-100">
                        <th class="p-3">ID</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Ensure we have a valid result set
                    if ($subjects_result && $subjects_result->num_rows > 0):
                        while ($subject = $subjects_result->fetch_assoc()): 
                    ?>
                    <tr class="border-b hover:bg-green-50">
                        <td class="p-3"><?php echo htmlspecialchars($subject['id']); ?></td>
                        <td class="p-3"><?php echo htmlspecialchars($subject['name']); ?></td>
                        <td class="p-3"><?php echo htmlspecialchars($subject['type']); ?></td>
                        <td class="p-3">
                            <a href="edit_subject.php?id=<?php echo $subject['id']; ?>" class="text-blue-500 mr-2">Edit</a>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                        echo "<tr><td colspan='4' class='text-center p-4'>No subjects found</td></tr>";
                    endif; 
                    ?>
                </tbody>
            </table>
        </div>

        <?php
        // Reset result pointers
       
        $timetable_result = $conn->query($timetable_query);
        ?>
        <!-- Timetable Section -->
        <div class="bg-white shadow-2xl rounded-2xl p-8">
            <h1 class="text-3xl font-bold mb-6 text-purple-800">Manage Timetable</h1>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-purple-100">
                            <th class="p-3">ID</th>
                            <th class="p-3">BRENCH</th>
                            <th class="p-3">Batch ID</th>
                            <th class="p-3">Semester</th>
                            <th class="p-3">Day</th>
                            <th class="p-3">Time Slot</th>
                            <th class="p-3">Time End</th>
                            <th class="p-3">Subject ID</th>
                            <th class="p-3">Teacher ID</th>
                            <th class="p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Ensure we have a valid result set
                        if ($timetable_result && $timetable_result->num_rows > 0):
                            while ($row = $timetable_result->fetch_assoc()): 
                        ?>
                        <tr class="border-b hover:bg-purple-50">
                            <td class="p-3"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['branch']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['batch_id']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['semester']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['day']); ?></td>
                            <td class="p-3"><?php echo date('g:i A', strtotime($row['time_slot'])); ?></td>
                            <td class="p-3"><?php echo date('g:i A', strtotime($row['time_slot_end'])); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['subject_id']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['teacher_id']); ?></td>
                            <td class="p-3">
                                
                                <form action="delete_timetable.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="text-red-500" onclick="return confirm('Are you sure?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else:
                            echo "<tr><td colspan='9' class='text-center p-4'>No timetable entries found</td></tr>";
                        endif; 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   


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