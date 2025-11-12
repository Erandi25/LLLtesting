<?php
session_start();

// ✅ Ensure the user is logged in and is a viewer
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'viewer') {
    header("Location: ../login_dbNew.php");
    exit();
}

// ✅ Connect to database
$host = 'localhost';
$db_username = 'root';
$password = '';
$database = 'lifelinelocator';

$conn = mysqli_connect($host, $db_username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = intval($_SESSION['UserID']);
$message = '';
$message_class = '';

// ✅ Initialize user array
$user = [
    'firstname' => '',
    'lastname' => '',
    'contact' => '',
    'email' => '',
    'urweight' => '',
    'bloodgroup' => '',
    'Province' => '',
    'district' => '',
    'conditions' => 0
];

// ✅ Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname   = trim(mysqli_real_escape_string($conn, $_POST['firstname'] ?? ''));
    $lastname    = trim(mysqli_real_escape_string($conn, $_POST['lastname'] ?? ''));
    $contact     = trim(mysqli_real_escape_string($conn, $_POST['contact'] ?? ''));
    $email       = trim(mysqli_real_escape_string($conn, $_POST['email'] ?? ''));
    $urweight    = floatval($_POST['urweight'] ?? 0);
    $bloodgroup  = trim(mysqli_real_escape_string($conn, $_POST['bloodgroup'] ?? ''));
    $province    = trim(mysqli_real_escape_string($conn, $_POST['Province'] ?? ''));
    $district    = trim(mysqli_real_escape_string($conn, $_POST['district'] ?? ''));
    $conditions  = isset($_POST['conditions']) ? 1 : 0;

    if (empty($firstname) || empty($lastname)) {
        $message = "First name and last name are required.";
        $message_class = 'alert-danger';
    } else {
        $sql = "UPDATE signup SET 
                    firstname = '$firstname',
                    lastname = '$lastname',
                    contact = '$contact',
                    email = '$email',
                    urweight = $urweight,
                    bloodgroup = '$bloodgroup',
                    Province = '$province',
                    district = '$district',
                    conditions = $conditions
                WHERE UserID = $user_id";

        if (mysqli_query($conn, $sql)) {
            $message = "✅ Profile updated successfully.";
            $message_class = 'alert-success';

            // Update session variables
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['contact'] = $contact;
            $_SESSION['email'] = $email;
            $_SESSION['urweight'] = $urweight;
            $_SESSION['bloodgroup'] = $bloodgroup;
            $_SESSION['Province'] = $province;
            $_SESSION['district'] = $district;

            // Update local data for form display
            $user = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'contact' => $contact,
                'email' => $email,
                'urweight' => $urweight,
                'bloodgroup' => $bloodgroup,
                'Province' => $province,
                'district' => $district,
                'conditions' => $conditions
            ];
        } else {
            $message = "❌ Update failed: " . mysqli_error($conn);
            $message_class = 'alert-danger';
        }
    }
}

// ✅ Load existing user data if not a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $sql = "SELECT firstname, lastname, contact, email, urweight, bloodgroup, Province, district, conditions 
            FROM signup WHERE UserID = $user_id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
    } else {
        $message = "⚠️ Failed to load profile.";
        $message_class = 'alert-warning';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="cssforeditpeofile.css"/>
</head>
<body>
<header>
    <div class="navbar">
        <nav><ul><li><a href="viewer.php">BACK</a></li></ul></nav>
    </div>
</header>

<main class="container mt-5">
    <h3 class="mb-4">✏️ Edit Profile</h3>

    <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="firstname" class="form-control" value="<?= htmlspecialchars($user['firstname'] ?? '') ?>" required />
        </div>
        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="lastname" class="form-control" value="<?= htmlspecialchars($user['lastname'] ?? '') ?>" required />
        </div>
        <div class="mb-3">
            <label>Contact no</label>
            <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($user['contact'] ?? '') ?>" required />
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required />
        </div>
        <div class="mb-3">
            <label>Blood Group</label>
            <select name="bloodgroup" class="form-control" required>
                <?php
                $groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                foreach ($groups as $group) {
                    $selected = ($user['bloodgroup'] ?? '') === $group ? 'selected' : '';
                    echo "<option value=\"$group\" $selected>$group</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label>User Weight (kg)</label>
            <input type="number" step="0.1" name="urweight" class="form-control" value="<?= htmlspecialchars($user['urweight'] ?? '') ?>" required />
        </div>
        <div class="mb-3">
            <label>Province</label>
            <input type="text" name="Province" class="form-control" value="<?= htmlspecialchars($user['Province'] ?? '') ?>" required />
        </div>
        <div class="mb-3">
            <label>District</label>
            <input type="text" name="district" class="form-control" value="<?= htmlspecialchars($user['district'] ?? '') ?>" required />
        </div>
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="conditions" value="1" <?= (!empty($user['conditions'])) ? 'checked' : '' ?> />
            <label class="form-check-label">I agree to the terms and conditions</label>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="viewer.php" class="btn btn-secondary">Cancel</a>
    </form>

    
</main>
<br><br>
<footer>
    <div class="footer-container">
      <div class="footer-column">
        <h4>ABOUT US</h4>
        <ul>
          <li><a href="http://localhost/LLLtesting/Aboutusnew.html">Purpose</a></li>
          <li><a href="http://localhost/LLLtesting/Privacy.html">Privacy Policy</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h4>NEWS & BLOG</h4>
        <ul>
          <li><a href="http://localhost/LLLtesting/camp.php">Camps</a></li>
          <li><a href="http://localhost/LLLtesting/hospital.html">Locations</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h4>RESOURCES</h4>
        <ul>
          <li><a href="http://localhost/LLLtesting/faq.html">FAQs</a></li>
          <li><a href="http://localhost/LLLtesting/Blooddonate.html">Blood donate</a></li>
          <li><a href="http://localhost/LLLtesting/Organdonate.html">Organ donate</a></li>
          <li><a href="http://localhost/LLLtesting/Contactus.html">Contact us</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h4>CALL US</h4>
        <p class="call-number">011 3455434</p>
        <h4>FOLLOW US</h4>
        <div class="social-icons">
          <a href="https://www.facebook.com/"><i class="fab fa-facebook"></i></a>
          <a href="https://web.whatsapp.com/"><i class="fab fa-whatsapp"></i></a>
          <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
          <a href="https://lk.linkedin.com/"><i class="fab fa-linkedin"></i></a>
          <a href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <p>Copyright © 2024 LifeLineLocator. All Rights Reserved.</p>
      <p>Terms of use | Privacy policy</p>
    </div>
  </footer>
</body>
</html>
