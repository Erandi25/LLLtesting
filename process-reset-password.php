<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Ensure token is provided
    if (empty($_POST["token"])) {
        die("Token missing.");
    }

    $token = $_POST["token"];
    $token_hash = hash("sha256", $token);


    $mysqli = require __DIR__ . "database.php";

    if (!($mysqli instanceof mysqli)) {
        die("Database connection failed.");
    }


    $sql = "SELECT * FROM signup WHERE reset_token_hash = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("s", $token_hash);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("Invalid token.");
    }

    if (strtotime($user["reset_token_expires_at"]) <= time()) {
        die("Token has expired.");
    }

    if ($_POST["password"] !== $_POST["password_confirmation"]) {
        die("Passwords do not match.");
    }

    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);


    $update_sql = "UPDATE signup
                   SET urpassword = ?, reset_token_hash = NULL, reset_token_expires_at = NULL
                   WHERE UserID = ?";
    $update_stmt = $mysqli->prepare($update_sql);

    if (!$update_stmt) {
        die("Update prepare failed: " . $mysqli->error);
    }

    $update_stmt->bind_param("si", $password_hash, $user["UserID"]);

    if ($update_stmt->execute()) {
        echo "Password updated successfully. <a href='login.html'>Go to Login</a>";
    } else {
        echo "Error updating password. " . $update_stmt->error;
    }
}
?>
