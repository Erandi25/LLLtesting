<?php
session_start();

// Database configuration
$host = 'localhost';
$db_username = 'root';
$password = '';
$database = 'lifelinelocator';

// Establish database connection
$conn = new mysqli($host, $db_username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message
$error = "";

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate inputs
    if (empty($username) || empty($password)) {
        $error = "Username and password are required";
    } else {
        // Prepare SQL to fetch user data
        $stmt = $conn->prepare("SELECT UserID, username, urpassword, role, firstname, lastname, 
                               contact, email, bloodgroup, urweight, Province, district 
                               FROM signup WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Verify password
            if (password_verify($password, $user['urpassword'])) {
                // Set session variables with all user data
                $_SESSION['UserID'] = $user['UserID'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['firstname'] = $user['firstname'];
                $_SESSION['lastname'] = $user['lastname'];
                $_SESSION['contact'] = $user['contact'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['bloodgroup'] = $user['bloodgroup'];
                $_SESSION['urweight'] = $user['urweight'];
                $_SESSION['Province'] = $user['Province'];
                $_SESSION['district'] = $user['district'];

                // Validate role and redirect
                $allowed_roles = ['admin', 'editor', 'viewer'];
                if (in_array($user['role'], $allowed_roles)) {
                    header("Location: dashboard/" . $user['role'] . ".php");
                    exit();
                } else {
                    $error = "Unauthorized access for this user role";
                }
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "User not found";
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
  <title>Sign in to Life Line Locator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #e6e6fa;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .login-card {
      max-width: 400px;
      width: 100%;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .btn-primary {
      background-color: #970430;
      border-color: #970430;
    }
    .btn-primary:hover {
      background-color: #7a0328;
      border-color: #7a0328;
    }
    .password-wrapper {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #888;
    }
    .toggle-password:hover {
      color: #000;
    }
  </style>
</head>
<body>
  <!-- Login Card -->
  <div class="card login-card p-4 shadow">
    <h3 class="text-center mb-4">Sign in to Life Line Locator</h3>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center py-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-3">
      <div class="mb-3">
        <input class="form-control" type="text" name="username" placeholder="Username" required>
      </div>
      <div class="mb-3 password-wrapper">
        <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
        <span class="toggle-password" onclick="togglePassword()" title="Show/Hide Password">👁️</span>
      </div>
      <button class="btn btn-primary w-100 mb-3" type="submit">Log in</button>
    </form>

    <!-- Links -->
    <div class="text-center mb-1">
      <a href="forgot-password.php" class="text-decoration-none">Forgot Password?</a>
    </div>
    <div class="text-center">
      No account? <a href="register.php" class="text-decoration-none">Create one</a>
    </div>
    <div class="text-center mt-2">
      <a href="Home.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById("password");
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
      } else {
        passwordInput.type = "password";
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
