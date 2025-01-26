
<?php
include 'db_connection.php';
include 'navigation.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body class="bg-gray-200 p-6">
    <h1 class="text-3xl mb-6 text-center">Admin Dashboard</h1>
    <div class="text-center mb-8">
        <a href="admin_manage.php" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Manage Teachers and Subjects</a>
    </div>

    <!-- Add Teacher Section -->
    <div class="mb-8">
        <h2 class="text-2xl mb-4">Add Teacher</h2>
        <form class="bg-white p-4 rounded shadow-md" action="your_add_teacher_script.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700" for="first_name">First Name</label>
                <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" type="text" id="first_name" name="first_name" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700" for="middle_name">Middle Name</label>
                <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" type="text" id="middle_name" name="middle_name">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700" for="last_name">Last Name</label>
                <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" type="text" id="last_name" name="last_name" required>
            </div>
            <button class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600" type="submit">Add Teacher</button>
        </form>
    </div>

    <!-- Add Subject Section -->
    <div class="mb-8">
        <h2 class="text-2xl mb-4">Add Subject</h2>
        <form class="bg-white p-4 rounded shadow-md" action="your_add_subject_script.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700" for="subject_name">Subject Name</label>
                <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" type="text" id="subject_name" name="subject_name" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700" for="subject_type">Subject Type</label>
                <select class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="subject_type" name="subject_type" required>
                    <option value="Theory">Theory</option>
                    <option value="Practical">Practical</option>
                </select>
            </div>
            <button class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600" type="submit">Add Subject</button>
        </form>
    </div>

    <!-- Generate Timetable Section -->
    <div>
        <h2 class="text-2xl mb-4">Generate Timetable</h2>
        <form class="bg-white p-4 rounded shadow-md" action="your_generate_timetable_script.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700" for="batch">Batch</label>
                <select class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="batch" name="batch" required>
                    <option value="C1">C1</option>
                    <option value="C2">C2</option>
                    <option value="C3">C3</option>
                    <!-- Add more batches as needed -->
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700" for="semester">Semester</label>
                <select class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="semester" name="semester" required>
                    <option value="1st year">1st year</option>
                    <option value="2nd year">2nd year</option>
                    <option value="3rd year">3rd year</option>
                    <!-- Add more semesters as needed -->
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700" for="day">Day</label>
                <select class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="day" name="day" required>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <!-- Add more days as needed -->
                </select>
            </div>
            <!-- <div class="mb-4">
                <label class="block text-gray-700" for="time_slot">Time Slot</label>
                <select class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="time_slot" name="time_slot" required>
                    <option value="9:00 AM - 10:00 AM">9:00 AM - 10:00 AM</option>
                    <option value="10:15 AM - 11:15 AM">10:15 AM - 11:15 AM</option>
                    <option value="11:15 AM - 12:15 AM">11:15 AM - 12:15 AM</option>
                    <option value="12:15 AM - 1:15 PM">12:15 AM - 1:15 PM</option>
                    <option value="01:40 AM - 02:40 PM">01:40 AM - 02:40 PM</option>
                    <option value="02:40 AM - 03:40 PM">02:40 AM - 03:40 PM</option>
                    <option value="03:45 AM - 04:45 PM">03:45 AM - 04:45 PM</option>
                    <option value="04:45 AM - 05:45 PM">04:45 AM - 05:45 PM</option>
                    <!-- Add more time slots as needed -->
                <!-- </select> -->
            <!-- </div> -->



            <div class="mb-4">
    <label for="time_slot" class="block text-gray-700 font-bold mb-2">Start Time:</label>
    <input 
        type="time" 
        id="time_slot" 
        name="time_slot" 
        value="<?php echo isset($_POST['start_time']) ? htmlspecialchars($_POST['start_time']) : ''; ?>" 
        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
        required
    >
</div>

<div class="mb-4">
    <label for="time_slot_end" class="block text-gray-700 font-bold mb-2">End Time:</label>
    <input 
        type="time" 
        id="time_slot_end" 
        name="time_slot_end" 
        value="<?php echo isset($timetable['time_slot_end']) ? htmlspecialchars($timetable['time_slot_end']) : ''; ?>" 
        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
        required
    >
</div>














            <div class="mb-4">
    <label class="block text-gray-700" for="subject">Subject</label>
    <select class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="subject" name="subject" required>
        <?php
        // Fetch subjects
        $subject_query = "SELECT name FROM subjects";
        $subject_result = $conn->query($subject_query);
        while ($row = $subject_result->fetch_assoc()) {
            echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
        }
        ?>
    </select>
</div>
<div class="mb-4">
    <label class="block text-gray-700" for="teacher">Teacher</label>
    <select class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="teacher" name="teacher" required>
        <?php
        // Fetch teachers
        $teacher_query = "SELECT CONCAT(first_name, ' ', last_name) AS full_name FROM teachers";
        $teacher_result = $conn->query($teacher_query);
        while ($row = $teacher_result->fetch_assoc()) {
            echo '<option value="' . $row['full_name'] . '">' . $row['full_name'] . '</option>';
        }
        ?>
    </select>
</div>


            <button class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600" type="submit">Generate Timetable</button>
        </form>
    </div>
</body>
</html>