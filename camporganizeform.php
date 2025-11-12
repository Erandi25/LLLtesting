<?php
// Include the database connection file
include('db_connect.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $campDate = mysqli_real_escape_string($conn, $_POST['CampDate']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $organizerNIC = mysqli_real_escape_string($conn, $_POST['organizerId']);
    $organizerName = mysqli_real_escape_string($conn, $_POST['organizerName']);
    $organizerEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $organizerContact = mysqli_real_escape_string($conn, $_POST['contactNumber']);
    $organizerAddress = mysqli_real_escape_string($conn, $_POST['address']);

    // SQL query to insert into the correct 'camps' table
    $sql = "INSERT INTO camps (CampDate, Location, OrganizerNIC, OrganizerName, OrganizerEmail, OrganizerContact, OrganizerAddress)
            VALUES ('$campDate', '$location', '$organizerNIC', '$organizerName', '$organizerEmail', '$organizerContact', '$organizerAddress')";

    // Execute the query and redirect or show error
    if (mysqli_query($conn, $sql)) {
        header("Location: camp.php");
        exit();
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}
?>


