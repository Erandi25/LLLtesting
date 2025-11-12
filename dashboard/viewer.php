<?php 
session_start();

// Check if user is logged in and has viewer role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'viewer') {
    header("Location: ../login_dbNew.php");
    exit();
}

// Database connection
$host = 'localhost';
$db_username = 'root';
$password = '';
$database = 'lifelinelocator';

$conn = new mysqli($host, $db_username, $password, $database);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Initialize user data with empty values
$user_data = [
    'firstname' => 'N/A',
    'lastname' => 'N/A',
    'contact' => 'N/A',
    'username' => 'N/A',
    'email' => 'N/A',
    'bloodgroup' => 'N/A',
    'urweight' => 'N/A',
    'Province' => 'N/A',
    'district' => 'N/A'
];

// First try to get data from session
if (isset($_SESSION['firstname'])) {
    foreach ($user_data as $key => $value) {
        if (isset($_SESSION[$key])) {
            $user_data[$key] = $_SESSION[$key];
        }
    }
}

// If any data is still N/A, fetch from database
if (in_array('N/A', $user_data) && (isset($_SESSION['UserID']) || isset($_SESSION['username']))) {
    $identifier = $_SESSION['UserID'] ?? $_SESSION['username'];
    $identifier_field = isset($_SESSION['UserID']) ? 'UserID' : 'username';
    
    $stmt = $conn->prepare("SELECT firstname, lastname, contact, username, email, bloodgroup, urweight, Province, district 
                          FROM signup WHERE $identifier_field = ?");
    if ($stmt) {
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $db_data = $result->fetch_assoc();
            $user_data = array_merge($user_data, $db_data);
            
            // Update session variables
            foreach ($db_data as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viewer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; display: flex; min-height: 100vh; margin: 0; }
        .sidebar { width: 250px; background-color: #4a154b; color: white; padding: 20px; position: fixed; height: 100vh; }
        .sidebar h4 { font-size: 1.2rem; margin-bottom: 1rem; }
        .sidebar .nav-link { color: white; padding: 10px 15px; border-radius: 5px; display: block; margin-bottom: 5px; transition: background-color 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: black; color: white; }
        .content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); }
        .navbar { background-color: #4a154b; }
        .navbar .navbar-brand { color: white; }
        .card { border-radius: 10px; }
        table th { background-color: #4a154b !important; color: white !important; }
        .quick-actions .btn { min-width: 180px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h4>👤 Viewer Panel</h4>
    <hr class="bg-light">
    <p><strong>👋 Hello,</strong> <?= htmlspecialchars($user_data['username']); ?></p>
    <ul class="nav flex-column mt-3">
        <li class="nav-item"><a class="nav-link active" href="viewer.php"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="allhospitals.php"><i class="bi bi-hospital"></i> View Hospitals</a></li>
        <li class="nav-item"><a class="nav-link" href="http://localhost/LLLtesting/Home.php"><i class="bi bi-box-arrow-right"></i> Back</a></li>
        <li class="nav-item"><a class="nav-link" href="../logouthome.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    </ul>
</div>

<div class="content">
    <nav class="navbar navbar-expand-lg rounded mb-4 px-3">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Welcome, <?= htmlspecialchars($user_data['username']); ?></a>
        </div>
    </nav>

    <div class="card shadow-sm p-4 mt-4 bg-white">
        <h5 class="mb-3">📊 Dashboard Overview</h5>
        <p>This is your control panel. Use the navigation to manage hospital details and view your profile information.</p>

        <div class="table-responsive mt-4">
            <h6 class="fw-bold">👤 User Details</h6>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Contact number</th>
                        <th>User name</th>
                        <th>Email</th>
                        <th>Blood Group</th>
                        <th>User weight</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($user_data['firstname']); ?></td>
                        <td><?= htmlspecialchars($user_data['lastname']); ?></td>
                        <td><?= htmlspecialchars($user_data['contact']); ?></td>
                        <td><?= htmlspecialchars($user_data['username']); ?></td>
                        <td><?= htmlspecialchars($user_data['email']); ?></td>
                        <td><?= htmlspecialchars($user_data['bloodgroup']); ?></td>
                        <td><?= htmlspecialchars($user_data['urweight']); ?></td>
                        <td><?= htmlspecialchars($user_data['Province']); ?></td>
                        <td><?= htmlspecialchars($user_data['district']); ?></td>
                        <td><a href="edit_profile.php" class="btn btn-warning btn-sm">Edit</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            <h6 class="fw-bold">📂 User History</h6>
            <p>Feature coming soon: Past activity, edits, requests, etc.</p>
        </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>