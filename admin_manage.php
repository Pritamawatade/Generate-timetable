<?php
require_once 'db_connection.php'; // Replace with your database connection file

// Handle delete operations
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = intval($_GET['delete']);
    $type = $_GET['type'];

    if ($type === 'teacher') {
        $delete_query = "DELETE FROM teachers WHERE id = ?";
    } elseif ($type === 'subject') {
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8 p-12">
        <h1 class="text-2xl font-bold mb-4">Manage Teachers</h1>
        <table class="min-w-full bg-white border rounded-xl mb-8 shadow-lg">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">First Name</th>
                    <th class="border px-4 py-2">Middle Name</th>
                    <th class="border px-4 py-2">Last Name</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($teacher = $teachers_result->fetch_assoc()): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $teacher['id']; ?></td>
                    <td class="border px-4 py-2"><?php echo $teacher['first_name']; ?></td>
                    <td class="border px-4 py-2"><?php echo $teacher['middle_name']; ?></td>
                    <td class="border px-4 py-2"><?php echo $teacher['last_name']; ?></td>
                    <td class="border px-4 py-2">
                        <a href="edit_teacher.php?id=<?php echo $teacher['id']; ?>" class="text-blue-500 hover:underline">Edit</a> | 
                        <a href="?delete=<?php echo $teacher['id']; ?>&type=teacher" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this teacher?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h1 class="text-2xl font-bold mb-4">Manage Subjects</h1>
        <table class="min-w-full bg-white border rounded-xl shadow-lg">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Type</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($subject = $subjects_result->fetch_assoc()): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $subject['id']; ?></td>
                    <td class="border px-4 py-2"><?php echo $subject['name']; ?></td>
                    <td class="border px-4 py-2"><?php echo $subject['type']; ?></td>
                    <td class="border px-4 py-2">
                        <a href="edit_subject.php?id=<?php echo $subject['id']; ?>" class="text-blue-500 hover:underline">Edit</a> | 
                        <a href="?delete=<?php echo $subject['id']; ?>&type=subject" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this subject?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

<h1 class="text-2xl font-bold mb-4">Manage Timetable</h1>
<table class="min-w-full bg-white border rounded-xl shadow-lg">
    <thead>
        <tr>
            <th class="py-2 px-4 border-b">ID</th>
            <th class="py-2 px-4 border-b">Batch ID</th>
            <th class="py-2 px-4 border-b">Semester</th>
            <th class="py-2 px-4 border-b">Day</th>
            <th class="py-2 px-4 border-b">Time Slot</th>
            <th class="py-2 px-4 border-b">Time End</th>
            <th class="py-2 px-4 border-b">Subject ID</th>
            <th class="py-2 px-4 border-b">Teacher ID</th>
            <th class="py-2 px-4 border-b">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $timetable_result->fetch_assoc()): ?>
            <tr>
                <td class="py-2 px-4 border-b"><?php echo $row['id']; ?></td>
                <td class="py-2 px-4 border-b"><?php echo $row['batch_id']; ?></td>
                <td class="py-2 px-4 border-b"><?php echo $row['semester']; ?></td>
                <td class="py-2 px-4 border-b"><?php echo $row['day']; ?></td>
                <td class="py-2 px-4 border-b">
                    <?php 
                        $time = strtotime($row['time_slot']);
                        echo date('g:i A', $time);
                    ?>
                </td>
                <td class="py-2 px-4 border-b">
                    <?php 
                        $time = strtotime($row['time_slot_end']);
                        echo date('g:i A', $time);
                    ?>
                </td>
                <td class="py-2 px-4 border-b"><?php echo $row['subject_id']; ?></td>
                <td class="py-2 px-4 border-b"><?php echo $row['teacher_id']; ?></td>
                <td class="py-2 px-4 border-b">
                    <a href="edit_timetable.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">Edit</a>
                    <form action="delete_timetable.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
                
    </div>
</body>
</html>
