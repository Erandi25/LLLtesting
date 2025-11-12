<?php
session_start(); // ✅ Required to use $_SESSION

$host = 'localhost';
$db_username = 'root';
$password = '';
$database = 'lifelinelocator';

$conn = new mysqli($host, $db_username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // ✅ Fetch full user details
    $stmt = $conn->prepare("SELECT UserID, username, urpassword, role FROM signup WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['urpassword'])) {
            // ✅ Set session variables
            $_SESSION['UserID']   = $user['UserID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            // ✅ Redirect based on role
            header("Location: dashboard/" . $user['role'] . ".php");
            exit();
        } else {
            $error = "❌ Invalid password.";
        }
    } else {
        $error = "❌ User not found.";
    }

    $stmt->close();
}

$conn->close();
?>

