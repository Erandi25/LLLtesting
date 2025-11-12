<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'editor') {
    header("Location: ../login_dbNew.php");
    exit();
}

include '../includes/db.php';

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editor - Manage Hospitals</title>
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
        .sidebar .nav-link {
            color: white;
            margin-bottom: 5px;
            transition: 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: black;
            border-radius: 5px;
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
    <h4>✏️ Editor Panel</h4>
    <hr class="bg-light">
    <p><strong>User:</strong> <?= htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?></p>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link active" href="editor.php">Manage Hospitals</a></li>
        <li class="nav-item"><a class="nav-link" href="http://localhost/LLLtesting/Home.php"><i class="bi bi-box-arrow-right"></i> Back</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <h4 class="mb-4">🏥 Edit Hospital Details</h4>

    <form method="GET" class="d-flex mb-4" style="width: 400px;">
        <input class="form-control me-2" type="search" name="search" placeholder="Search hospitals..." value="<?= htmlspecialchars($searchTerm); ?>">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th>#</th>
                <th>Hospital Name</th>
                <th>Contact</th>
                <th>Available Blood</th>
                <th>Needed Blood</th>
                <th>Comments</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody class="text-center">
        <?php
        $sql = !empty($searchTerm)
            ? "SELECT * FROM hospital WHERE pname LIKE ? OR pdes LIKE ?"
            : "SELECT * FROM hospital";

        $stmt = $conn->prepare($sql);

        if (!empty($searchTerm)) {
            $like = "%$searchTerm%";
            $stmt->bind_param("ss", $like, $like);
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
            <td><?= htmlspecialchars($row['available_blood'] ?? ''); ?></td>
            <td><?= htmlspecialchars($row['needed_blood'] ?? ''); ?></td>
            <td><?= nl2br(htmlspecialchars($row['comments'] ?? '-')); ?></td>
            <td><a href="edit_hospital.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a></td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="7">No hospitals found.</td></tr>
        <?php endif; $stmt->close(); $conn->close(); ?>
        </tbody>
    </table>

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
