<?php
// Include database connection
include 'db_connect.php';

$successMessage = ""; // Initialize success message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $patient_name = $_POST['patientname'] ?? '';
    $patient_age = $_POST['patientage'] ?? '';
    $contact_number = $_POST['contactNumber'] ?? '';
    $reason = $_POST['reason'] ?? '';
    $blood_group = $_POST['bloodGroup'] ?? '';
    $nearest_hospital = $_POST['nearestHospital'] ?? '';

    // Handle file upload
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $attachment = $_FILES['attachment']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($attachment);

        // Ensure the uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
            // Insert data into the database
            $sql = "INSERT INTO BloodRequests (patient_name, patient_age, contact_number, reason, blood_group, nearest_hospital, attachment)
                    VALUES ('$patient_name', '$patient_age', '$contact_number', '$reason', '$blood_group', '$nearest_hospital', '$target_file')";

            if ($conn->query($sql) === TRUE) {
                $successMessage = "Your submission was a successful submission.";
            } else {
                $successMessage = "Error: " . $conn->error;
            }
        } else {
            $successMessage = "Error uploading file.";
        }
    } else {
        $successMessage = "Please attach a valid file.";
    }
}

$conn->close();
?>

<!-- HTML Part (place this where your form is displayed) -->
<?php if (!empty($successMessage)): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb; border-radius: 4px;">
        <?php echo $successMessage; ?>
    </div>
<?php endif; ?>

<!-- Your form starts here -->
<form method="POST" enctype="multipart/form-data">
    <!-- Form fields (patientname, patientage, contactNumber, etc.) -->
</form>
