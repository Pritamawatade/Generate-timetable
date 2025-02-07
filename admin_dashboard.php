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
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.1), 0 10px 10px rgba(0, 0, 0, 0.08);
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
    <div class="mb-4 fixed top-20 " style="right: 8%;">

        <a href='teacher_logout.php' class="z-5">
            <button
                class='text-lg bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-110 home'>
                Logout
            </button>
        </a>
    </div>
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-center mb-12 text-blue-800 tracking-tight">
            Admin Dashboard
        </h1>


        <div class="grid md:grid-cols-3 gap-8">
            <!-- Manage Section -->
            <div
                class="card bg-white rounded-2xl shadow-lg p-6 flex items-center justify-center transform transition-all hover:scale-105 ">
                <h2 class="text-md font-semibold mb-4 text-blue-700">Quick Actions</h2>
                <a href="admin_manage.php"
                    class="block w-full bg-blue-500 text-white py-3 rounded-lg text-center hover:bg-blue-600 transition-colors">
                    Manage Teachers & Subjects
                </a>
            </div>

            <!-- Add Teacher Section -->
            <div class="card bg-white rounded-2xl shadow-lg p-6 transform transition-all hover:scale-105">
                <h2 class="text-2xl font-semibold mb-4 text-blue-700">Add Teacher</h2>
                <form action="your_add_teacher_script.php" method="POST" class="space-y-4">
                    <input type="text" name="first_name" placeholder="First Name" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <input type="text" name="middle_name" placeholder="Middle Name"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <input type="text" name="last_name" placeholder="Last Name" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                        class="w-full bg-green-500 text-white py-3 rounded-lg hover:bg-green-600 transition-colors">
                        Add Teacher
                    </button>
                </form>
            </div>

            <!-- Add Subject Section -->
            <div class="card bg-white rounded-2xl shadow-lg p-6 transform transition-all hover:scale-105 ">
                <h2 class="text-2xl font-semibold mb-4 text-blue-700">Add Subject</h2>

                <!-- 
                <form action="your_add_subject_script.php" method="POST" class="space-y-4">
                    <input type="text" name="subject_name" placeholder="Subject Name" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <select name="subject_type" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="Theory">Theory</option>
                        <option value="LAB">LAB</option>
                    </select>
                    <button type="submit"
                        class="w-full bg-purple-500 text-white py-3 rounded-lg hover:bg-purple-600 transition-colors">
                        Add Subject
                    </button>
                </form> -->


                <form action="your_add_subject_script.php" method="POST" class="space-y-4">
                    <!-- Branch Selection -->
                    <select name="branch" id="branch1" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Branch</option>
                        <option value="Computer Engineering">Computer Engineering</option>
                        <option value="Medical Electronics">Medical Electronics</option>
                        <option value="Electronics And Telecommunication">Electronics And Telecommunication</option>
                        <option value="Dress Design And Garment Manufacturing">Dress Design And Garment Manufacturing
                        </option>
                        <option value="Civil Engineering">Civil Engineering</option>
                    </select>

                    <!-- Semester Selection (Will be populated dynamically) -->
                    <select name="semester" id="semester1" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Semester</option>
                        <!-- Options will be populated dynamically using JavaScript -->
                    </select>

                    <!-- Subject Name -->
                    <input type="text" name="subject_name" placeholder="Subject Name" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">

                    <!-- Subject Type -->
                    <select name="subject_type" required
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="Theory">Theory</option>
                        <option value="LAB">LAB</option>
                    </select>

                    <button type="submit"
                        class="w-full bg-purple-500 text-white py-3 rounded-lg hover:bg-purple-600 transition-colors">
                        Add Subject
                    </button>
                </form>

<!-- // semesster logic for subject -->
                <script>
                    // Define semester options for each branch
                    const semesterOptions1 = {
                        "Computer Engineering": ["CO1K", "CO2K", "CO3K", "CO4K", "CO5K", "CO5I", "CO6K", "CO6I"],
                        "Medical Electronics": ["MEO1", "MEO2", "MEO3", "MEO4", "MEO5", "MEO6"],
                        "Electronics And Telecommunication": ["ETO1", "ETO2", "ETO3", "ETO4", "ETO5", "ETO6"],
                        "Dress Design And Garment Manufacturing": ["DDO1", "DDO2", "DDO3", "DDO4", "DDO5", "DDO6"],
                        "Civil Engineering": ["CEO1", "CEO2", "CEO3", "CEO4", "CEO5", "CEO6"]
                    };

                    // Get references to select elements
                    const branchSelect1 = document.getElementById("branch1");
                    const semesterSelect1 = document.getElementById("semester1");

                    // Function to update semester dropdown
                    function updateSemesters1() {


                        const selectedBranch = branchSelect1.value;
                        const semesters = semesterOptions1[selectedBranch] || [];

                        semesterSelect1.innerHTML = '<option value="">Select Semester</option>';
                        semesters.forEach(sem => {
                            const option = document.createElement("option");
                            option.value = sem;
                            option.textContent = sem;
                            semesterSelect1.appendChild(option);
                        });
                    }

                    // Run update function when branch changes
                    branchSelect1.addEventListener("change", updateSemesters1);
                </script>


            </div>

            <div class=" bg-white rounded-2xl shadow-xl p-8 mb-32 col-span-3 z-50">
                <!-- Header Section -->
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-blue-800">
                        <i class="fas fa-calendar-plus mr-3"></i>Generate Timetable
                    </h2>
                    <p class="text-gray-600 mt-2">Create and schedule new timetable entries</p>
                </div>

                <form action="your_generate_timetable_script.php" method="POST" class="space-y-8">
                    <!-- Branch & Semester Section -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="branch" class="block text-sm font-medium text-gray-700">Branch</label>
                            <select name="branch" id="branch" required
                                class="block w-full p-3 text-gray-700 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                <option value="">Select Branch</option>
                                <option value="Computer Engineering">Computer Engineering</option>
                                <option value="Medical Electronics">Medical Electronics</option>
                                <option value="Electronics And Telecommunication">Electronics And Telecommunication
                                </option>
                                <option value="Dress Design And Garment Manufacturing">Dress Design And Garment
                                    Manufacturing</option>
                                <option value="Civil Engineering">Civil Engineering</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                            <select name="semester" id="semester" required
                                class="block w-full p-3 text-gray-700 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                <option value="">Select Semester</option>
                            </select>
                        </div>
                    </div>

                    <!-- Day & Time Section -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="day" class="block text-sm font-medium text-gray-700">Day</label>
                            <select name="day" required
                                class="block w-full p-3 text-gray-700 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                <option value="">Select Day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Time Slot</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="time_slot" class="block text-xs text-gray-500">Start Time</label>
                                    <input type="time" name="time_slot" required
                                        class="block w-full p-3 text-gray-700 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                </div>
                                <div>
                                    <label for="time_slot_end" class="block text-xs text-gray-500">End Time</label>
                                    <input type="time" name="time_slot_end" required
                                        class="block w-full p-3 text-gray-700 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subject & Teacher Section -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <select id="subject" name="subject" required
                                class="block w-full p-3 text-gray-700 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                <option value="">Select Subject</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="teacher" class="block text-sm font-medium text-gray-700">Teacher</label>
                            <select name="teacher" required
                                class="block w-full p-3 text-gray-700 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                <option value="">Select Teacher</option>
                                <?php
                                $teacher_query = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM teachers";
                                $teacher_result = $conn->query($teacher_query);
                                while ($row = $teacher_result->fetch_assoc()) {
                                    echo '<option value="' . $row['id'] . '">' . $row['full_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Lab & Batch Section (Hidden by Default) -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="batch" class="block text-sm font-medium text-gray-700 hidden">Batch</label>
                            <select name="batch" id="batch"
                                class="hidden block w-full p-3 text-gray-700 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                <option value="">Select Batch</option>
                                <option value="C1">C1</option>
                                <option value="C2">C2</option>
                                <option value="C3">C3</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="lab_allocation" class="block text-sm font-medium text-gray-700 hidden">Lab
                                Allocation</label>
                            <select id="lab_allocation" name="lab_allocation"
                                class="hidden block w-full p-3 text-gray-700 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                <option value="">Select Lab</option>
                                <option value="LAB1">LAB1</option>
                                <option value="LAB2">LAB2</option>
                                <option value="LAB3">LAB3</option>
                                <option value="LAB4">LAB4</option>
                                <option value="LAB5">LAB5</option>
                                <option value="DH-1">DH-1</option>
                                <option value="DH-2">DH-2</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-all duration-300 text-lg font-semibold flex items-center justify-center">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Generate Timetable
                        </button>
                    </div>
                </form>

                <!-- Original Scripts -->
                <script>
                    // Define semester options for each branch
                    const semesterOptions = {
                        "Computer Engineering": ["CO1K", "CO2K", "CO3K", "CO4K", "CO5K", "CO5I", "CO6K", "CO6I"],
                        "Medical Electronics": ["MEO1", "MEO2", "MEO3", "MEO4", "MEO5", "MEO6"],
                        "Electronics And Telecommunication": ["ETO1", "ETO2", "ETO3", "ETO4", "ETO5", "ETO6"],
                        "Dress Design And Garment Manufacturing": ["DDO1", "DDO2", "DDO3", "DDO4", "DDO5", "DDO6"],
                        "Civil Engineering": ["CEO1", "CEO2", "CEO3", "CEO4", "CEO5", "CEO6"]
                    };

                    const branchSelect = document.getElementById("branch");
                    const semesterSelect = document.getElementById("semester");

                    function updateSemesters() {
                        const selectedBranch = branchSelect.value;
                        const semesters = semesterOptions[selectedBranch] || [];
                        semesterSelect.innerHTML = '<option value="">Select Semester</option>';

                        semesters.forEach(sem => {
                            const option = document.createElement("option");
                            option.value = sem;
                            option.textContent = sem;
                            semesterSelect.appendChild(option);
                        });
                    }

                    branchSelect.addEventListener("change", updateSemesters);
                    updateSemesters();

                    const semesterElement = document.getElementById("semester");
                    const branchElement = document.getElementById("branch");
                    const subjectElement = document.getElementById("subject");
                    const labField = document.getElementById('lab_allocation');
                    const batchField = document.getElementById('batch');

                    semesterElement.addEventListener("change", function () {
                        const selectedOption = this.options[this.selectedIndex];
                        const subjectType = selectedOption.getAttribute('data-type');

                        if(!selectedOption || !subjectType || subjectType === 'Theory' || selectedOption.value === '') {
                            labField.classList.add('hidden');
                            labField.required = false;
                            batchField.classList.add('hidden');
                            batchField.required = false;
                        }
                        const branch = branchElement.value;
                        const semester = this.value;

                        fetch(`get_subjects.php?branch=${encodeURIComponent(branch)}&semester=${encodeURIComponent(semester)}`)
                            .then(response => response.json())
                            .then(data => {
                                subjectElement.innerHTML = '<option value="">Select Subject</option>';
                                data.forEach(subject => {
                                    const option = document.createElement("option");
                                    option.value = subject.id;
                                    option.textContent = subject.name + " (" + subject.type + ")";
                                    option.setAttribute("data-type", subject.type);
                                    subjectElement.appendChild(option);
                                });
                            })
                            .catch(error => {
                                console.error("Error fetching subjects:", error);
                            });
                    });

                    subjectElement.addEventListener('change', function () {
                        const selectedOption = this.options[this.selectedIndex];
                        const subjectType = selectedOption.getAttribute('data-type');

                        if(!selectedOption || !subjectType || subjectType === 'Theory' || selectedOption.value === '') {
                            labField.classList.add('hidden');
                            labField.required = false;
                            batchField.classList.add('hidden');
                            batchField.required = false;
                        }
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


        <script>
            // GSAP Animations

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