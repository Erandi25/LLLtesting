<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Correct path to db.php from inside /dashboard
include "../includes/db.php";

// Restrict access to admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_dbNew.php");
    exit();
}

// Handle search
$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

if (!empty($searchTerm)) {
    $sql = "SELECT UserID, firstname, lastname, contact, username, email, bloodgroup, urweight, Province, district, role 
            FROM signup 
            WHERE firstname LIKE ? OR lastname LIKE ? OR username LIKE ? OR email LIKE ? OR contact LIKE ? OR role LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTermLike = "%$searchTerm%";
    $stmt->bind_param("ssssss", $searchTermLike, $searchTermLike, $searchTermLike, $searchTermLike, $searchTermLike, $searchTermLike);
} else {
    $sql = "SELECT UserID, firstname, lastname, contact, username, email, bloodgroup, urweight, Province, district, role FROM signup";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users</title>
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
            padding: 30px;
            width: calc(100% - 250px);
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
        <li class="nav-item"><a class="nav-link" href="addhospitalpage.php">Add Hospitals</a></li>
        <li class="nav-item"><a class="nav-link" href="allhospitals.php">View Hospitals</a></li>
        <li class="nav-item"><a class="nav-link active" href="allusers.php">All Users</a></li>
        <li class="nav-item"><a class="nav-link" href="http://localhost/LLLtesting/Home.php"><i class="bi bi-box-arrow-right"></i> Back</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Content -->
<div class="content">
    <h4 class="mb-4">👥 All Registered Users</h4>

    <form method="get" class="d-flex mb-4" style="width: 400px;">
        <input class="form-control me-2" type="search" name="search" placeholder="Search users..." value="<?= htmlspecialchars($searchTerm) ?>">
        <button class="btn btn-primary" type="submit">Search</button>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>UserID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Contact</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Blood Group</th>
                        <th>Weight</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['UserID']) ?></td>
                            <td><?= htmlspecialchars($row['firstname']) ?></td>
                            <td><?= htmlspecialchars($row['lastname']) ?></td>
                            <td><?= htmlspecialchars($row['contact']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['bloodgroup']) ?></td>
                            <td><?= htmlspecialchars($row['urweight']) ?></td>
                            <td><?= htmlspecialchars($row['Province']) ?></td>
                            <td><?= htmlspecialchars($row['district']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row['role']) ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No users found.</div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
