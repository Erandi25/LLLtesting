<?php
// Include the database connection file
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name']?? '';
    $age = $_POST['age']?? '';
    $date = $_POST['date']?? '';
    $contact = $_POST['contact']?? '';
    $weight = $_POST['weight']?? '';
    $bloodGroup = $_POST['bloodGroup']?? '';
    $nearestHospital = $_POST['nearestHospital']?? '';
    $conditions = isset($_POST['conditions']) ? 1 : 0; // Checkbox value (1 = checked, 0 = unchecked)

    // Prepare the SQL statement
    $sql = "INSERT INTO bloodDonations (name, age, donation_date, contact_no, weight, blood_group, nearest_hospital, agreed_conditions)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissdssi", $name, $age, $date, $contact, $weight, $bloodGroup, $nearestHospital, $conditions);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect to the home page after successful submission
        header("Location: Home.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
