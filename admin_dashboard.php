
<?php
include 'db_connection.php';
// include 'navigation.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == 'admin' && $password == 'pass123') {
        // login successful
        header("Location: admin_dashboard.php");
        // exit();
    } else {
        echo "<script>alert('wrong id password');</script>";
        echo "<script>window.history.back();</script>";
    }
}
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- GSAP for Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f6f8f9 0%, #e5ebee 100%);
        }
        .card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .card:hover {
            box-shadow: 0 14px 28px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.08);
            transform: translateY(-10px);
        }
    </style>
    <style>
        body {
            background: linear-gradient(135deg, #f6f8f9 0%, #e5ebee 100%);
            opacity: 1 !important;
        }
        * {
            opacity: 1 !important;
        }
    </style>
</head>
<body class="min-h-screen px-8 py-16">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-center mb-12 text-blue-800 tracking-tight">
            Admin Dashboard
        </h1>
        <a href='index.php'>
            <button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-110 home'>
                <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 19l-7-7m0 0l7-7m-7 7h18'></path></svg>
                Back to Home
            </button>
        </a>
    <a href='teacher_logout.php'>
            <button class='bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-110 home'>
                logout
            </button>
        </a>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Manage Section -->
            <div class="card bg-white rounded-2xl shadow-lg p-6 transform transition-all hover:scale-105">
                <h2 class="text-2xl font-semibold mb-4 text-blue-700">Quick Actions</h2>
                <a href="admin_manage.php" class="block w-full bg-blue-500 text-white py-3 rounded-lg text-center hover:bg-blue-600 transition-colors">
                    Manage Teachers & Subjects
                </a>
            </div>

            <!-- Add Teacher Section -->
            <div class="card bg-white rounded-2xl shadow-lg p-6 transform transition-all hover:scale-105">
                <h2 class="text-2xl font-semibold mb-4 text-blue-700">Add Teacher</h2>
                <form action="your_add_teacher_script.php" method="POST" class="space-y-4">
                    <input type="text" name="first_name" placeholder="First Name" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <input type="text" name="middle_name" placeholder="Middle Name" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <input type="text" name="last_name" placeholder="Last Name" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-lg hover:bg-green-600 transition-colors">
                        Add Teacher
                    </button>
                </form>
            </div>

            <!-- Add Subject Section -->
            <div class="card bg-white rounded-2xl shadow-lg p-6 transform transition-all hover:scale-105 ">
                <h2 class="text-2xl font-semibold mb-4 text-blue-700">Add Subject</h2>
                <form action="your_add_subject_script.php" method="POST" class="space-y-4">
                    <input type="text" name="subject_name" placeholder="Subject Name" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <select name="subject_type" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="Theory">Theory</option>
                        <option value="LAB">LAB</option>
                    </select>
                    <button type="submit" class="w-full bg-purple-500 text-white py-3 rounded-lg hover:bg-purple-600 transition-colors">
                        Add Subject
                    </button>
                </form>
            </div>

       <!-- Generate Timetable Section -->
<div class="card bg-white rounded-2xl shadow-lg p-8 transform transition-all hover:scale-105 col-span-3 mb-32">
    <h2 class="text-3xl font-semibold mb-6 text-blue-800 text-center">Generate Timetable</h2>
    <form action="your_generate_timetable_script.php" method="POST" class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="space-y-4">
            <select name="branch" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select Branch</option>
                <option value="Computer Engineering">Computer Engineering</option>
                <option value="Medical Electronics">Medical Electronics</option>
                <option value="Electronics And Telecommunication">Electronics And Telecommunication</option>
                <option value="Dress Design And Garment Manufacturing">Dress Design And Garment Manufacturing</option>
                <option value="Civil Engineering">Civil Engineering</option>
            </select>

            <select name="semester" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select Semester</option>
                <option value="CO1K">CO1K</option>
                <option value="CO2K">CO2K</option>
                <option value="CO3K">CO3K</option>
                <option value="CO4K">CO4K</option>
                <option value="CO5K">CO5K</option>
                <option value="CO6K">CO6K</option>
            </select>
        </div>
        <div class="space-y-4">
            <select name="day" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select Day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
            </select>
            <div class="grid grid-cols-2 gap-4">
                <input type="time" name="time_slot" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <input type="time" name="time_slot_end" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div class="md:col-span-2 space-y-4">
            <select id="subject" name="subject" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select Subject</option>
                <?php
                $subject_query = "SELECT id, name, type FROM subjects";
                $subject_result = $conn->query($subject_query);
                while ($row = $subject_result->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '" data-type="' . $row['type'] . '">' . $row['name'] . ' (' . $row['type'] . ')</option>';
                }
                ?>
            </select>
            <select id="lab_allocation" name="lab_allocation" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 hidden">
                <option value="">Select Lab</option>
                <option value="LAB1">LAB1</option>
                <option value="LAB2">LAB2</option>
                <option value="LAB3">LAB3</option>
                <option value="LAB4">LAB4</option>
            </select>

            <select name="batch" id="batch" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 hidden">
                <option value="">Select Batch</option>
                <option value="C1">C1</option>
                <option value="C2">C2</option>
                <option value="C3">C3</option>
            </select>

            <select name="teacher" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select Teacher</option>
                <?php
                $teacher_query = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM teachers";
                $teacher_result = $conn->query($teacher_query);
                while ($row = $teacher_result->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . $row['full_name'] . '</option>';
                }
                ?>
            </select>
            <button type="submit" class="w-full bg-indigo-500 text-white py-4 rounded-lg hover:bg-indigo-600 transition-colors text-lg font-semibold">
                Generate Timetable
            </button>
        </div>
    </form>

    <script>
        // Show/Hide Lab Allocation and Batch fields dynamically
        document.getElementById('subject').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const subjectType = selectedOption.getAttribute('data-type');
            const labField = document.getElementById('lab_allocation');
            const batchField = document.getElementById('batch');

            if (subjectType === 'LAB') {
                labField.classList.remove('hidden');
                labField.required = true;
                batchField.classList.remove('hidden');
                batchField.required = true;
            } else {
                labField.classList.add('hidden');
                labField.required = false;
                batchField.classList.add('hidden');
                batchField.required = false;
            }
        });
    </script>
</div>


    </div>

    <div class="fixed bottom-10 right-10 cursor-pointer">
        <a href="index.php">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-110 home">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Home
            </button>
        </a>
    </div>

    <script>
        // GSAP Animations
        gsap.from(".card", {
            opacity: 0,
            y: 50,
            stagger: 0.9999,
            duration: 0.8,
            ease: "power2.out"
        });
    </script> 


  

    <script>
        // GSAP Animations
        gsap.from(".fixed .home", {
            opacity: 1,
            scale: 0.8,
            duration: 0.8,
            ease: "power2.out",
            delay: 1
        });
    </script>
    
</body>
</html>