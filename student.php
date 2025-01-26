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
            <div>
                <label for="semester" class="block text-gray-700 font-bold mb-2">Select Semester:</label>
                <select id="semester" name="semester" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Select Semester --</option>
                    <option value="1st year">1st Year</option>
                    <option value="2nd year">2nd Year</option>
                    <option value="3rd year">3rd Year</option>
                </select>
            </div>

            <div>
                <label for="batch" class="block text-gray-700 font-bold mb-2">Select Batch:</label>
                <select id="batch" name="batch" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Select Batch --</option>
                    <option value="C1">Batch C1</option>
                    <option value="C2">Batch C2</option>
                    <option value="C3">Batch C3</option>
                </select>
            </div>

            <div class="flex justify-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">View Timetable</button>
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
    <!-- <script>
        // Handle form submission
        document.getElementById('timetableForm').addEventListener('submit', async function (event) {
            event.preventDefault();

            const semester = document.getElementById('semester').value;
            const batch = document.getElementById('batch').value;

            if (!semester || !batch) {
                alert('Please select both semester and batch.');
                return;
            }

            try {
                // Fetch timetable data from backend
                const response = await fetch('backend.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ batch, semester })
            });
                if (!response.ok) {
                    throw new Error('Failed to fetch timetable.');
                }

                const data = await response.json();
                console.log("data = ",data);
                
                // Display timetable
                displayTimetable(data);
            } catch (error) {
                console.error(error);
                alert('An error occurred while fetching the timetable.');
            }
        });

        function displayTimetable(data) {
            const timetableSection = document.getElementById('timetableSection');
            const timetableContainer = document.getElementById('timetableContainer');

            // Clear previous timetable
            timetableContainer.innerHTML = '';

            // Create timetable table
            const table = document.createElement('table');
            table.className = 'table-auto border-collapse border border-gray-300 w-full';

            // Add header row (time slots)
            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            headerRow.className = 'bg-gray-200';

            const daysColumn = document.createElement('th');
            daysColumn.className = 'border border-gray-300 px-4 py-2';
            daysColumn.textContent = 'Days / Time';
            headerRow.appendChild(daysColumn);

            // Add time slots
            const timeSlots = ['10:45 AM - 11:45 AM', '11:45 AM - 12:45 PM', '12:45 PM - 1:45 PM', '1:45 PM - 2:45 PM', '2:45 PM - 3:45 PM', '3:45 PM - 4:45 PM'];
            timeSlots.forEach(slot => {
                const th = document.createElement('th');
                th.className = 'border border-gray-300 px-4 py-2';
                th.textContent = slot;
                headerRow.appendChild(th);
            });

            thead.appendChild(headerRow);
            table.appendChild(thead);

            // Add timetable data
            const tbody = document.createElement('tbody');

            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            days.forEach(day => {
                const row = document.createElement('tr');

                const dayCell = document.createElement('td');
                dayCell.className = 'border border-gray-300 px-4 py-2 font-bold';
                dayCell.textContent = day;
                row.appendChild(dayCell);

                timeSlots.forEach(slot => {
                    const cell = document.createElement('td');
                    cell.className = 'border border-gray-300 px-4 py-2';

                    const lecture = data[day]?.[slot] || '-'; // Check if lecture exists for this slot
                    cell.textContent = lecture;

                    row.appendChild(cell);
                });

                tbody.appendChild(row);
            });

            table.appendChild(tbody);
            timetableContainer.appendChild(table);

            // Show timetable section
            timetableSection.classList.remove('hidden');
        }
    </script> -->
</body>
</html>
