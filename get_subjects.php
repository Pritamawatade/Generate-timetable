<?php
// Include database connection
include 'db_connection.php';

// Check if branch and semester are set
if (!isset($_GET['branch']) || !isset($_GET['semester'])) {
    echo json_encode(["error" => "Missing branch or semester"]);
    exit;
}

// Get branch and semester from request
$branch = $conn->real_escape_string($_GET['branch']);
$semester = $conn->real_escape_string($_GET['semester']);

error_log("🔎 Received Request: Branch = $branch | Semester = $semester");

// Fetch subjects for the given branch and semester
$query = "SELECT id, name, type FROM subjects WHERE branch='$branch' AND semester='$semester'";
$result = $conn->query($query);

$subjects = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
} else {
    error_log("❌ Database Error: " . $conn->error);
}

// Debugging: Log response before sending
error_log("📤 Sending Response: " . json_encode($subjects));

// Return JSON response
header('Content-Type: application/json');
echo json_encode($subjects);

// Close the database connection
$conn->close();
?>
