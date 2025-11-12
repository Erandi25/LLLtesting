<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_dbNew.php");
    exit();
}

include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }
        .sidebar {
            width: 250px;
            background-color: #4a154b;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar h4 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        .sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            display: block;
            margin-bottom: 5px;
            transition: background-color 0.2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: black;
            color: #fff;
        }
        .content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 30px;
        }
        .navbar {
            background-color: #4a154b;
        }
        .navbar .navbar-brand {
            color: white;
        }
        .card {
            border: none;
            border-radius: 12px;
        }
        .alert {
            border-radius: 12px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Admin Panel</h4>
    <hr class="bg-light">
    <p><strong>User:</strong> <?= htmlspecialchars($_SESSION['username']); ?></p>
    <ul class="nav flex-column mt-3">
        <li class="nav-item"><a class="nav-link active" href="dashboard/admin.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="addhospitalpage.php">Add Hospitals</a></li>
        <li class="nav-item"><a class="nav-link" href="allhospitals.php">View Hospitals</a></li>
        <li class="nav-item"><a class="nav-link" href="allusers.php">All Users</a></li>
        <li class="nav-item"><a class="nav-link" href="http://localhost/LLLtesting/Home.php"><i class="bi bi-box-arrow-right"></i> Back</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <nav class="navbar navbar-expand-lg rounded mb-4 px-3">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Welcome, <?= htmlspecialchars($_SESSION['username']); ?></a>
        </div>
    </nav>

    <div class="alert alert-info text-center shadow-sm"><h6 class="mb-0">Welcome Admin - <?= htmlspecialchars($_SESSION['username']); ?>!</h6></div>

    <div class="card shadow-sm p-4 mt-4 bg-white">
        <h5 class="mb-3">📊 Dashboard Overview</h5>
        <p>This is your control panel. Use the navigation to manage hospital details, users, and system data.</p>
    </div>

        <!-- Add Camp Organize Banners Section -->
    <div class="mt-5">
        <h5 class="mb-3">📢 Add Camp Organize Banners or Special Requests</h5>
        <form action="upload_banner.php" method="POST" enctype="multipart/form-data" class="p-4 border rounded bg-white shadow-sm" style="max-width: 600px;">
            <div class="mb-3">
                <label for="banner" class="form-label">Choose banner image</label>
                <input class="form-control" type="file" name="banner" id="banner" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success"><i class="bi bi-upload"></i> Upload Banner</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



</body>
</html>
