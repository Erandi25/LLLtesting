<?php

$host = 'localhost';
$db_username = 'root';
$password = '';
$database = 'lifelinelocator';

$conn = mysqli_connect($host, $db_username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pname = '';
$pdes = '';
$available_blood = '';
$needed_blood = '';
$special_comments = '';
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $pname = trim(mysqli_real_escape_string($conn, $_POST['pname']));
    $pdes = trim(mysqli_real_escape_string($conn, $_POST['pdes']));
    $available_blood = trim(mysqli_real_escape_string($conn, $_POST['available_blood']));
    $needed_blood = trim(mysqli_real_escape_string($conn, $_POST['needed_blood']));
    $special_comments = trim(mysqli_real_escape_string($conn, $_POST['special_comments']));

    if (empty($pname)) {
        $error = "Name (pname) is required.";
    } else {
        $sql = "UPDATE hospital SET
                pname = '$pname',
                pdes = '$pdes',
                available_blood = '$available_blood',
                needed_blood = '$needed_blood',
                special_comments = '$special_comments'
                WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            $success = "Record updated successfully.";
        } else {
            $error = "Error updating record: " . mysqli_error($conn);
        }
    }
}


if ($id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $sql = "SELECT * FROM hospital WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $pname = $row['pname'];
        $pdes = $row['pdes'];
        $available_blood = $row['available_blood'];
        $needed_blood = $row['needed_blood'];
        $special_comments = $row['special_comments'];
    } else {
        $error = "Record not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Hospital Record</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="cssforeditpeofile.css"/>
</head>

<header>
    <div class="navbar">
        <nav><ul><li><a href="Editor.php">BACK</a></li></ul></nav>
    </div>
</header>
<body>

<div  class="container mt-5">
  <h2 class="mb-4">Edit Hospital Record</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  
  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

    <div class="mb-3">
      <label for="pname" class="form-label">Hospital Name</label>
      <input type="text" class="form-control" id="pname" name="pname" value="<?= htmlspecialchars($pname) ?>" required>
    </div>

    <div class="mb-3">
      <label for="pdes" class="form-label">Hospital Description</label>
      <input type="text" class="form-control" id="pdes" name="pdes" value="<?= htmlspecialchars($pdes) ?>" required>
    </div>

    <div class="mb-3">
      <label for="available_blood" class="form-label">Available Blood Types</label>
      <textarea class="form-control" id="available_blood" name="available_blood" rows="3"><?= htmlspecialchars($available_blood) ?></textarea>
    </div>

    <div class="mb-3">
      <label for="needed_blood" class="form-label">Needed Blood Types</label>
      <textarea class="form-control" id="needed_blood" name="needed_blood" rows="3"><?= htmlspecialchars($needed_blood) ?></textarea>
    </div>

    <div class="mb-3">
      <label for="special_comments" class="form-label">Special Comments</label>
      <textarea class="form-control" id="special_comments" name="special_comments" rows="3"><?= htmlspecialchars($special_comments) ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update Record</button>
    <a href="editor.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
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
