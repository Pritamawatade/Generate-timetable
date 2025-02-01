<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Timetable</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center min-w-screen">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center text-gray-700 mb-6">View Timetable</h1>

        <!-- Form for selecting semester and batch -->
        <form id="timetableForm" action="backend.php" method="POST" class="space-y-4">
    <!-- Branch Selection -->
    <div>
        <label for="branch" class="block text-gray-700 font-bold mb-2">Select Branch:</label>
        <select id="branch" name="branch" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="updateSemesters()">
            <option value="">Select Branch</option>
            <option value="Computer Engineering">Computer Engineering</option>
            <option value="Medical Electronics">Medical Electronics</option>
            <option value="Electronics And Telecommunication">Electronics And Telecommunication</option>
            <option value="Dress Design And Garment Manufacturing">Dress Design And Garment Manufacturing</option>
            <option value="Civil Engineering">Civil Engineering</option>
        </select>
    </div>

    <!-- Semester Selection -->
    <div>
        <label for="semester" class="block text-gray-700 font-bold mb-2">Select Semester:</label>
        <select id="semester" name="semester" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <option value="">Select Semester</option>
        </select>
    </div>

       <!-- Submit Button -->
       <div class="flex justify-center">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    View Timetable
                </button>
            </div>
</form>












<script>
    function updateSemesters() {
        // Define semester mappings for each branch
        const semesterOptions = {
            "Computer Engineering": ["CO1K", "CO2K", "CO3K", "CO4K", "CO5K", "CO5i", "CO6K", "CO6i"],
            "Medical Electronics": ["MEO1", "MEO2", "MEO3", "MEO4", "MEO5", "MEO6"],
            "Electronics And Telecommunication": ["ETO1", "ETO2", "ETO3", "ETO4", "ETO5", "ETO6"],
            "Dress Design And Garment Manufacturing": ["DDO1", "DDO2", "DDO3", "DDO4", "DDO5", "DDO6"],
            "Civil Engineering": ["CEO1", "CEO2", "CEO3", "CEO4", "CEO5", "CEO6"]
        };

        // Get selected branch
        let selectedBranch = document.getElementById("branch").value;
        let semesterDropdown = document.getElementById("semester");

        // Clear existing semester options
        semesterDropdown.innerHTML = `<option value="">Select Semester</option>`;

        // If a valid branch is selected, populate semesters
        if (selectedBranch in semesterOptions) {
            semesterOptions[selectedBranch].forEach(sem => {
                let option = document.createElement("option");
                option.value = sem;
                option.textContent = sem;
                semesterDropdown.appendChild(option);
            });
        }
    }
</script>

        <!-- Timetable Display Section -->

        <div id="timetableSection" class="mt-6 hidden w-full ">
            <h2 class="text-xl font-bold text-gray-700 text-center mb-4">Timetable</h2>
            <div id="timetableContainer" class="">
                <!-- Timetable will be dynamically inserted here -->
            </div>
        </div>
    </div>

</body>

</html>