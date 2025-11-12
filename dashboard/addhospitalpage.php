<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_db.php");
    exit();
}

include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Hospital - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #4a154b;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
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
            padding: 40px;
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Admin Panel</h4>
    <hr class="bg-light">
    <p><strong>User:</strong> <?= htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?></p>
    <ul class="nav flex-column mt-3">
        <li class="nav-item"><a class="nav-link" href="admin.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="addhospitalpage.php">Add Hospitals</a></li>
        <li class="nav-item"><a class="nav-link" href="allhospitals.php">View Hospitals</a></li>
        <li class="nav-item"><a class="nav-link" href="allusers.php">All Users</a></li>
        <li class="nav-item"><a class="nav-link" href="http://localhost/LLLtesting/Home.php"><i class="bi bi-box-arrow-right"></i> Back</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <div class="container bg-white p-5 rounded shadow-sm">
        <h2 class="mb-4">Add Hospital</h2>

        <form class="row g-3" method="POST">
            <div class="col-md-9">
                <label class="form-label">Hospital Name</label>
                <input type="text" class="form-control border border-secondary" name="pname" required>
            </div>

            <div class="col-12">
                <label class="form-label">Contact Details</label>
                <textarea class="form-control border border-secondary" name="pdes" rows="4" required></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Currently Available Blood Types</label>
                <input type="text" class="form-control border border-secondary" name="available_blood" placeholder="e.g., A+, B-, O+">
            </div>

            <div class="col-md-6">
                <label class="form-label">Needed Blood Types</label>
                <input type="text" class="form-control border border-secondary" name="needed_blood" placeholder="e.g., AB-, O-, B+">
            </div>

            <div class="col-12">
                <label class="form-label">Special Comments</label>
                <textarea class="form-control border border-secondary" name="special_comments" rows="3"></textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary" name="submit">Add Hospital</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $pname = trim($_POST['pname']);
    $pdes = trim($_POST['pdes']);
    $available = trim($_POST['available_blood']);
    $needed = trim($_POST['needed_blood']);
    $comments = trim($_POST['special_comments']);

    if ($pname && $pdes) {
        $check_sql = "SELECT * FROM hospital WHERE pname = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $pname);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Hospital with this name already exists.');</script>";
        } else {
            $insert_sql = "INSERT INTO hospital (pname, pdes, available_blood, needed_blood, special_comments) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sssss", $pname, $pdes, $available, $needed, $comments);

            if ($insert_stmt->execute()) {
                echo "<script>alert('Hospital added successfully!'); window.location.href='addhospitalpage.php';</script>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>
