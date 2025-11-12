<?php
// Include the database connection file
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $patientName = $_POST['patientname'] ?? '';
    $patientAge = $_POST['patientage'] ?? '';
    $contactNumber = $_POST['contactNumber'] ?? '';
    $reason = $_POST['reason'] ?? '';
    $organType = $_POST['organtype'] ?? '';
    $nearestHospital = $_POST['nearestHospital'] ?? '';

    // Handle file upload
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $attachment = $_FILES['attachment'];
        $uploadDir = 'uploads/'; // Directory to save uploaded files
        $uploadFile = $uploadDir . basename($attachment['name']);

        // Ensure the uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the upload directory
        if (move_uploaded_file($attachment['tmp_name'], $uploadFile)) {
            // Insert data into the database
            $sql = "INSERT INTO organ_requests (patient_name, patient_age, contact_number, reason, organ_type, nearest_hospital, attachment_path) 
                    VALUES ('$patientName', $patientAge, '$contactNumber', '$reason', '$organType', '$nearestHospital', '$uploadFile')";
            
            if ($conn->query($sql) === TRUE) {
                // Redirect to Home.html after successful submission
                header("Location: Home.html");
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Failed to upload the file.";
        }
    } else {
        echo "Please attach a valid file.";
    }
}

// Close the database connection
$conn->close();
?>
