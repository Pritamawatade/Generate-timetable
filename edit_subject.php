<?php
// Include database connection
require_once 'db_connection.php';

// Fetch the teacher data based on the ID from the URL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $subject = $result->fetch_assoc();
    $stmt->close();

    // Redirect back if the subject is not found
    if (!$subject) {
        echo "<script>alert('Subject not found!'); window.location='admin_manage.php';</script>";
        exit;
    }
}

// Handle form submission to update the teacher data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);

    // Validate input
    if (empty($first_name) || empty($last_name)) {
        echo "<script>alert('First name and Last name are required!');</script>";
    } else {
        // Update query
        $update_query = "UPDATE subjects SET name = ?, type = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $first_name, $last_name, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Subject updated successfully!'); window.location='admin_manage.php';</script>";
        } else {
            echo "<script>alert('Error updating subject.');</script>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-4">Edit Subject</h1>
            <form method="POST" action="edit_subject.php">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($subject['id']); ?>">

                <div class="mb-4">
                    <label for="first_name" class="block text-gray-700 font-bold mb-2">Subject Name:</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($subject['name']); ?>" 
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

              

                <div class="mb-4">
                    <label for="last_name" class="block text-gray-700 font-bold mb-2">Subject Type:</label>
                    <select id="last_name" name="last_name" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="LAB" <?php echo $subject['type'] == 'LAB' ? 'selected' : ''; ?>>LAB</option>
                        <option value="Theory" <?php echo $subject['type'] == 'Theory' ? 'selected' : ''; ?>>Theory</option>
                    </select>
                </div>

                <div class="flex justify-between items-center">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Changes</button>
                    <a href="admin_manage.php" class="text-gray-600 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
