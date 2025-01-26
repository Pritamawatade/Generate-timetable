<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $delete_query = "DELETE FROM timetable WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Deleted successfully!'); window.location.href='admin_manage.php';</script>";
    } else {
        echo "<script>alert('Error deleting timetable entry.');</script>";
    }
    $stmt->close();
}

exit();
?>
