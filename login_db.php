<?php
session_start();
include "includes/db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch user by username
    $stmt = $conn->prepare("SELECT * FROM signup WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['urpassword'])) {
            // Set session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['UserID'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect to Home.php
            header("Location: Home.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Username not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Life Line Locator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
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
<body class="d-flex flex-column align-items-center" style="min-height: 100vh; background: #e6e6fa;">

  <!-- Login Card -->
  <div class="card p-4 shadow" style="max-width: 400px; width: 100%; border-radius: 12px;">
    <h3 class="text-center mb-4" style="color:black;">Sign in to Life Line Locator</h3>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center py-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-3">
      <input class="form-control mb-3" type="text" name="username" placeholder="User Name" required>

      <div class="password-wrapper mb-3">
        <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
        <span class="toggle-password" onclick="togglePassword()" title="Show/Hide Password">
          👁️
        </span>
      </div>

      <button class="btn w-100 mb-3" type="submit" style="background-color: #970430; color: white;">Log in</button>
    </form>

    <!-- Google Login Button -->
    <button class="google-button w-100 mb-3 btn btn-outline-secondary d-flex align-items-center justify-content-center" onclick="signIn()">
      <img src="logogoogle.png" alt="Google logo" style="width: 20px; margin-right: 8px;" />
      Continue with Google
    </button>

    <!-- Links -->
    <div class="text-center mb-1">
      <a href="forgot-password.php" class="text-decoration-none">Forgot Password</a>
    </div>
    <div class="text-center">
      No account? <a href="register.php" class="text-decoration-none">Create one</a>
    </div>
    <div class="auth-buttons text-center mt-2">
      <a href="Home.php" class="btn btn-light">Cancel</a>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    function togglePassword() {
      const passwordInput = document.getElementById("password");
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
      } else {
        passwordInput.type = "password";
      }
    }

    function signIn() {
      let oauth2Endpoint = "https://accounts.google.com/o/oauth2/v2/auth";
      let form = document.createElement('form');
      form.setAttribute('method', 'GET');
      form.setAttribute('action', oauth2Endpoint);

      let params = {
        "client_id": "660377743847-0upkp4tgsn53ban9ffd7l5ho6u39qg6g.apps.googleusercontent.com",
        "redirect_uri": "http://127.0.0.1:5500/Home.php",
        "response_type": "token",
        "scope": "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email",
        "include_granted_scopes": 'true',
        "state": 'pass-through-value'
      };

      for (let p in params) {
        let input = document.createElement('input');
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', p);
        input.setAttribute('value', params[p]);
        form.appendChild(input);
      }

      document.body.appendChild(form);
      form.submit();
    }
  </script>
</body>
</html>
