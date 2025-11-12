<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Allow admin, viewer, and editor roles
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'viewer', 'editor'])) {
    header("Location: ../login_dbNew.php");
    exit();
}

include '../includes/db.php';

// Handle search input
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Hospitals - Blood Bank</title>
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
        }
        .sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            display: block;
            margin-bottom: 5px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: black;
            color: white;
        }
        .content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }

        /* ✅ Blood type highlight styles */
        .available-blood {
            background-color: #d4edda; /* light green */
            color: #026018ff;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
        .needed-blood {
            background-color: #f8d7da; /* light red */
            color: #71020dff;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>🩸 Blood Bank Panel</h4>
    <hr class="bg-light">
    <p><strong>User:</strong> <?= htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?></p>
    <ul class="nav flex-column mt-3">
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="admin.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="addhospitalpage.php">Add Hospitals</a></li>
            <li class="nav-item"><a class="nav-link active" href="allhospitals.php">View Hospitals</a></li>
            <li class="nav-item"><a class="nav-link" href="allusers.php">All Users</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="viewer.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active" href="allhospitals.php">View Hospitals</a></li>
            <li class="nav-item"><a class="nav-link" href="http://localhost/LLLtesting/Home.php"><i class="bi bi-box-arrow-right"></i> Back</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="http://localhost/LLLtesting/Home.php"><i class="bi bi-box-arrow-right"></i> Back</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <h4 class="mb-4">🏥 All Hospitals</h4>

    <form method="GET" class="d-flex mb-4" style="width: 400px;">
        <input class="form-control me-2" type="search" name="search" placeholder="Search hospitals..." value="<?= htmlspecialchars($searchTerm); ?>">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
    </form>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success text-center">Hospital deleted successfully.</div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th>#</th>
                <th>Hospital Name</th>
                <th>Contact Details</th>
                <th>Available Blood Types</th>
                <th>Needed Blood Types</th>
                <th>Special Comments</th>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody class="text-center">
        <?php
        if (!empty($searchTerm)) {
            $sql = "SELECT * FROM hospital WHERE pname LIKE ? OR pdes LIKE ? OR available_blood LIKE ? OR needed_blood LIKE ? OR special_comments LIKE ?";
            $stmt = $conn->prepare($sql);
            $search = "%$searchTerm%";
            $stmt->bind_param("sssss", $search, $search, $search, $search, $search);
        } else {
            $sql = "SELECT * FROM hospital";
            $stmt = $conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $count = 1;

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $count++; ?></td>
            <td><?= htmlspecialchars($row['pname'] ?? ''); ?></td>
            <td><?= nl2br(htmlspecialchars($row['pdes'] ?? '')); ?></td>
            <td><span class="available-blood"><?= htmlspecialchars($row['available_blood'] ?? ''); ?></span></td>
            <td><span class="needed-blood"><?= htmlspecialchars($row['needed_blood'] ?? ''); ?></span></td>
            <td><?= nl2br(htmlspecialchars($row['special_comments'] ?? '-')); ?></td>
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <td>
                <form method="POST" action="delete_hospital.php" onsubmit="return confirm('Are you sure you want to delete this hospital?');">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id'] ?? ''); ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
            <?php endif; ?>
        </tr>
        <?php
            endwhile;
        else:
            echo '<tr><td colspan="' . ($_SESSION['role'] === 'admin' ? '7' : '6') . '">No hospitals found.</td></tr>';
        endif;

        $stmt->close();
        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
