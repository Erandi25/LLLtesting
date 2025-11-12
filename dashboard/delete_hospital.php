<?php
session_start();

// Only admin can delete
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include '../includes/db.php'; // Adjust path if needed

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if ($id <= 0) {
        header("Location: allhospitals.php?msg=invalid_id");
        exit();
    }

    try {
        $stmt = $conn->prepare("DELETE FROM hospital WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: allhospitals.php?msg=deleted");
            exit();
        } else {
            throw new Exception("Deletion failed: " . $stmt->error);
        }

    } catch (Exception $e) {
        error_log($e->getMessage());
        header("Location: allhospitals.php?msg=error");
        exit();
    }

} else {
    header("Location: allhospitals.php");
    exit();
}
