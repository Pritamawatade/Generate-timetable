<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = intval($_POST['teacher_id']);
    $password = $_POST['password'];

    // Validate teacher credentials
    $sql = "SELECT * FROM teachers_login WHERE teacher_id = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $teacher_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['teacher_id'] = $teacher_id;
        header("Location: teacher_timetable.php");
        exit();
    } else {
        $error = "Invalid ID or Password.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teacher Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="flex items-center justify-center h-screen bg-gray-100">
        <form class="bg-white p-6 rounded shadow-md" method="POST">
            <h2 class="text-2xl mb-4">Teacher Login</h2>
            <div class="mb-4">
                <label class="block text-gray-700" for="teacher_id">Teacher ID:</label>
                <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" type="number" id="teacher_id" name="teacher_id" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700" for="password">Password:</label>
                <input class="mt-1 block w-full border border-gray-300 rounded-md p-2" type="password" id="password" name="password" required>
            </div>
            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded" type="submit">Login</button>
            <?php if (isset($error)) echo "<p class='text-red-600'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>
