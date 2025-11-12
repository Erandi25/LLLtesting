<?php
// Include the database connection file
include('db_connect.php');
// Get form values (ensure form uses POST)
$name = $_POST['name'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$subject = $_POST['subject'];
$message = $_POST['message'];

// Prepare SQL statement
$sql = "INSERT INTO contact_messages(name, email, contact, subject, message) 
VALUES('$name', '$email', '$contact', '$subject', '$message')";



if (mysqli_query($conn, $sql)) {
    // Redirect to camp.html after successful insertion
    header("Location: Contactus.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}


?>