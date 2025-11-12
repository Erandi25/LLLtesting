<?php
$host = 'localhost';
$db_username = 'root';
$password = '';
$database = 'lifelinelocator';

$conn = new mysqli($host, $db_username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $urpassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $bloodgroup = $_POST['bloodgroup'] ?? '';
    $urweight = $_POST['weight'] ?? '';
    $Province = $_POST['Province'] ?? '';
    $district = $_POST['district'] ?? '';
    $conditions = isset($_POST['conditions']) ? 1 : 0;

    // Handle file upload
    $medicalReportPath = "";
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $targetDir = "uploads/"; // Directory to store uploaded files
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create directory if not exists
        }
        $medicalReportPath = $targetDir . basename($_FILES["attachment"]["name"]);
        move_uploaded_file($_FILES["attachment"]["tmp_name"], $medicalReportPath);
    }

    $stmt = $conn->prepare("INSERT INTO signup 
        (firstname, lastname, contact, email, username, urpassword, bloodgroup, urweight, Province, district, conditions) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssissi", $firstname, $lastname, $contact, $email, $username, $urpassword, $bloodgroup, $urweight, $Province, $district, $conditions);

    if ($stmt->execute()) {
        echo "Data entered successfully";

        // Redirect to the login page
        header("Location: login.html");
        exit(); // Ensure no further code is executed after the redirect
    } else {
        echo "Failed: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

