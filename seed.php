<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "students";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the students table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    age INT,
    email VARCHAR(255)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Seed student records
$students = [
    ['John Doe', 19, 'johndoe@gmail.com'],
    ['Bob Smith', 19, 'bobsmith@gmail.com'],
    ['Rahaat Hussain', 20, 'rahaathussain@gmail.com'],
    ['Amber Hill', 18, 'amberhill@gmail.com'],
    ['David Wilson', 18, 'davidwilson@gmail.com']
];

foreach ($students as $student) {
    $name = $student[0];
    $age = $student[1];
    $email = $student[2];

    // Prepare and execute the SQL query
    $sql = "INSERT INTO students (name, age, email) VALUES ('$name', $age, '$email')";
    if ($conn->query($sql) === TRUE) {
        echo "Student record inserted successfully<br>";
    } else {
        echo "Error inserting student record: " . $conn->error . "<br>";
    }
}

// Close the database connection
$conn->close();
?>
