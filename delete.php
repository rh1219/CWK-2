<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "students";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if any student records are selected for deletion
    if (isset($_POST["selectedStudents"]) && !empty($_POST["selectedStudents"])) {
        $selectedStudents = $_POST["selectedStudents"];


        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Escape and sanitize the selected students
        $escapedSelectedStudents = array_map(function ($studentId) use ($conn) {
            return mysqli_real_escape_string($conn, $studentId);
        }, $selectedStudents);

        $selectedStudentsString = implode(",", $selectedStudents);

        // Delete the selected student records from the database
        $sql = "DELETE FROM students WHERE id IN ($selectedStudentsString)";
        if ($conn->query($sql) === TRUE) {
            echo "Selected student records deleted successfully.";
        } else {
            echo "Error deleting student records: " . $conn->error;
        }

        // Close the database connection
        $conn->close();
    } else {
        echo "No student records selected for deletion.";
    }
} else {
    echo "Invalid request.";
}
?>
