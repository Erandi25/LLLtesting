<?php
$host = 'localhost';
$user = 'root';
$password = ''; // XAMPP/WAMP default
$database = 'lifelinelocator';

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hospital_id = trim($_POST['hospital_id']);
    $hospital_name = trim($_POST['hospital_name']);
    $location = trim($_POST['location']);
    $contact = trim($_POST['contact']);

    if (!empty($hospital_id) && !empty($hospital_name) && !empty($location) && !empty($contact)) {
        $stmt = $conn->prepare("INSERT INTO hospitalregistration (hospital_id, hospital_name, location, contact) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $hospital_id, $hospital_name, $location, $contact);

        if ($stmt->execute()) {
            $success = "Hospital registered successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hospital Registration Page</title>
  <link rel="stylesheet" href="registration_host.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="container">
    <!-- Back Icon -->
    <a href="hospital.html" class="back-icon" title="Go Back">
        <img src="backButton.png" width="20px" height="20px">
    </a>
    <br><br>

    <h1><b>Hospital Registration</b></h1>

    <!-- Success / Error messages -->
    <?php if (!empty($success)): ?>
        <div style="color: green;"><?= $success ?></div>
    <?php elseif (!empty($error)): ?>
        <div style="color: red;"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="hospital_id">Hospital ID</label>
            <input type="text" id="hospital_id" name="hospital_id" placeholder="Enter Government Registration Number" required>
        </div>
        <div class="form-group">
            <label for="hospital_name">Hospital Name</label>
            <input type="text" id="hospital_name" name="hospital_name" placeholder="Enter Hospital Name" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" placeholder="Enter Location" required>
        </div>
        <div class="form-group">
            <label for="contact">Contact</label>
            <input type="tel" id="contact" name="contact" placeholder="Enter Contact" required>
        </div>

        <div class="button-container">
            <button type="submit" class="register-button">Register</button>
            <button type="reset" class="cancel-button">Cancel</button>
        </div>
    </form>
</div>
</body>
</html>
