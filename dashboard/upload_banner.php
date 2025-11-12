<?php
session_start();

// Allow both editor and admin roles
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['editor', 'admin'])) {
    header("Location: ../login_dbNew.php");
    exit();
}

include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['banner'])) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = basename($_FILES['banner']['name']);
    $newName = time() . "_" . $fileName;
    $targetFile = $uploadDir . $newName;

    if (move_uploaded_file($_FILES['banner']['tmp_name'], $targetFile)) {
        // Save the filename in the DB
        $stmt = $conn->prepare("INSERT INTO banners (filename) VALUES (?)");
        $stmt->bind_param("s", $newName);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        // Redirect based on role
        if ($_SESSION['role'] === 'admin') {
            echo "<script>alert('Banner uploaded successfully.'); window.location.href = 'admin.php';</script>";
        } else {
            echo "<script>alert('Banner uploaded successfully.'); window.location.href = 'editor.php';</script>";
        }
    } else {
        // Upload failed
        if ($_SESSION['role'] === 'admin') {
            echo "<script>alert('Upload failed.'); window.location.href = 'admin.php';</script>";
        } else {
            echo "<script>alert('Upload failed.'); window.location.href = 'editor.php';</script>";
        }
    }
} else {
    // Redirect if accessed without a POST request
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: editor.php");
    }
    exit();
}
?>

