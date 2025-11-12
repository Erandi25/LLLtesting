<?php
// Include database connection
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect form data
    $patient_name = $_POST['patientname'];
    $patient_age = (int)$_POST['patientage'];
    $contact_number = $_POST['contactNumber'];
    $reason = $_POST['reason'];
    $organ_type = $_POST['organtype'];
    $nearest_hospital = $_POST['nearestHospital'];
    $agreed_conditions = isset($_POST['conditions']) ? 1 : 0;

    // Handle file upload
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['attachment']['tmp_name'];
        $file_name = $_FILES['attachment']['name'];
        $upload_dir = 'uploads/';

        // Ensure upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_dest_path = $upload_dir . basename($file_name);

        if (move_uploaded_file($file_tmp_path, $file_dest_path)) {
            // Insert data into database
            $stmt = $conn->prepare(
                "INSERT INTO OrganDonationDetails (patient_name, patient_age, contact_number, reason, organ_type, nearest_hospital, medical_report_path, agreed_conditions)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sisssssi",
                $patient_name,
                $patient_age,
                $contact_number,
                $reason,
                $organ_type,
                $nearest_hospital,
                $file_dest_path,
                $agreed_conditions
            );

            if ($stmt->execute()) {
                // Redirect to home page after successful submission
                header("Location: Home.html");
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Failed to upload the file.";
        }
    } else {
        echo "Please attach a valid file.";
    }
}

$conn->close();
?>
