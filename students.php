<!DOCTYPE html>
<html>
<head>
    <title>Student Records</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
    <script>
        function confirmDelete() {
            var result = confirm("Are you sure you want to delete the selected student records?");
            if (result) {
                document.getElementById("deleteForm").submit();
            }
        }
    </script>
</head>
<body>
<div class="container mt-4">
    <h1>Student Records</h1>
    <form id="deleteForm" method="post" action="delete.php">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th>Select</th>
                <th>Name</th>
                <th>Age</th>
                <th>Email</th>
                <th>Password</th>
                <th>Image</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Database configuration
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "students";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve student records
            $sql = "SELECT * FROM students";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='selectedStudents[]' value='" . $row["id"] . "'></td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["age"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["password"] . "</td>";
                    echo "<td><img src='" . $row["image"] . "' alt='Student Image' style='max-width: 100px;'></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No student records found</td></tr>";
            }

            // Close the database connection
            $conn->close();
            ?>
            </tbody>
        </table>
        <br>
        <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Selected</button>
    </form>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>