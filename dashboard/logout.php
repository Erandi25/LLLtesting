<?php
// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to public hospital page or login
header("Location: ../Home.php"); // ✅ Adjust path if needed
exit();



