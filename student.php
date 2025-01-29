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
        <select id="branch" name="branch"
            class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
        <select id="semester" name="semester"
            class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <option value="">Select Semester</option>
            <option value="CO1K">CO1K</option>
            <option value="CO2K">CO2K</option>
            <option value="CO3K">CO3K</option>
            <option value="CO4K">CO4K</option>
            <option value="CO5K">CO5K</option>
            <option value="CO6K">CO6K</option>
        </select>
    </div>

    <!-- Batch Selection (Optional) -->
    <!-- <div>
        <label for="batch" class="block text-gray-700 font-bold mb-2">Select Batch (For Labs Only):</label>
        <select id="batch" name="batch"
            class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Select Batch --</option>
            <option value="C1">Batch C1</option>
            <option value="C2">Batch C2</option>
            <option value="C3">Batch C3</option>
        </select>
    </div> -->

    <!-- Submit Button -->
    <div class="flex justify-center">
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            View Timetable
        </button>
    </div>
</form>


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