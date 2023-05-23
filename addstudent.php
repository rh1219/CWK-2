<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1>Add Student</h1>

    <?php
    $nameErr = $ageErr = $emailErr = $passwordErr = $imageErr = "";
    $name = $age = $email = $password = $image = "";

    function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate name
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = sanitizeInput($_POST["name"]);
            // Check if name contains only letters and whitespace
            if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                $nameErr = "Only letters and white space allowed";
            }
        }

        // Validate age
        if (empty($_POST["age"])) {
            $ageErr = "Age is required";
        } else {
            $age = sanitizeInput($_POST["age"]);
            // Check if age is a positive integer
            if (!filter_var($age, FILTER_VALIDATE_INT) || $age <= 0) {
                $ageErr = "Age must be a positive integer";
            }
        }

        // Validate email
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            $email = sanitizeInput($_POST["email"]);
            // Check if email address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }

        // Validate and store the password
        if (empty($_POST["password"])) {
            $passwordErr = "Password is required";
        } else {
            $password = $_POST["password"];

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        }

        // File upload
        if (isset($_FILES["image"])) {
            $targetDir = "uploads/";  // Directory to store the uploaded images
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if the file is an image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                $imageErr = "File is not an image.";
            }

            // Certain file formats are allowed
            $allowedFormats = array("jpg", "jpeg", "png");
            if (!in_array($imageFileType, $allowedFormats)) {
                $imageErr = "Only JPG, JPEG, and PNG files are allowed.";
            }

            // Move the uploaded file to the directory
            if (empty($imageErr)) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    $image = $targetFile;
                } else {
                    $imageErr = "Error uploading the image.";
                }
            }
        }

// If there are no errors, insert student details into the database
        if (empty($nameErr) && empty($ageErr) && empty($emailErr) && empty($passwordErr) && empty($imageErr)) {
            // ...

            // Create a connection to the database
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Sanitise the input before inserting into the database
            $name = mysqli_real_escape_string($conn, $name);
            $age = mysqli_real_escape_string($conn, $age);
            $email = mysqli_real_escape_string($conn, $email);
            $hashedPassword = mysqli_real_escape_string($conn, $hashedPassword);
            $image = mysqli_real_escape_string($conn, $image);

            // Execute the SQL statement to insert details
            $stmt = $conn->prepare("INSERT INTO students (name, age, email, password, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("siss", $name, $age, $email, $hashedPassword, $image);
            if ($stmt->execute() === TRUE) {
                echo "<div class='alert alert-success'>Student details inserted successfully.</div>";
                // Clear the form inputs
                $name = $age = $email = $password = $image = "";
            } else {
                echo "<div class='alert alert-danger'>Error inserting student details: " . $conn->error . "</div>";
            }

            // Close the database connection
            $stmt->close();
            $conn->close();
        }
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
            <span class="error"><?php echo $nameErr; ?></span>
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="text" class="form-control" id="age" name="age" value="<?php echo $age; ?>">
            <span class="error"><?php echo $ageErr; ?></span>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
            <span class="error"><?php echo $emailErr; ?></span>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password">
            <span class="error"><?php echo $passwordErr; ?></span>
        </div>

        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" class="form-control-file" id="image" name="image">
            <span class="error"><?php echo $imageErr; ?></span>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>