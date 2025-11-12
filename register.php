<?php
include "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname   = $_POST['firstname'];
    $lastname    = $_POST['lastname'];
    $contact     = $_POST['contact'];
    $email       = $_POST['email'];
    $username    = $_POST['username'];
    $password    = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $bloodgroup  = $_POST['bloodgroup'];
    $urweight    = $_POST['weight'];
    $province    = $_POST['province'];
    $district    = $_POST['district'];
    $role        = $_POST['role'];
    $conditions  = isset($_POST['conditions']) ? 1 : 0;

    $attachmentPath = "";
    if (isset($_FILES["attachment"]) && $_FILES["attachment"]["error"] == 0) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $attachmentPath = $uploadDir . basename($_FILES["attachment"]["name"]);
        move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachmentPath);
    }

    $stmt = $conn->prepare("SELECT * FROM signup WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $existing = $stmt->get_result();

    if ($existing->num_rows > 0) {
        $message = "<p style='color:red;'>Username already taken. Choose another.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO signup (firstname, lastname, contact, username, urpassword, email, bloodgroup, urweight, Province, district, role, conditions) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssi", $firstname, $lastname, $contact, $username, $password, $email, $bloodgroup, $urweight, $province, $district, $role, $conditions);

        if ($stmt->execute()) {
            $message = "<p style='color:green;'>Registration successful!</p>";
        } else {
            $message = "<p style='color:red;'>Error: " . $stmt->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Registration</title>
  <link rel="stylesheet" href="RegistrationStyles.css">
</head>
<body>
  <div id="organizerDetailsForm" class="form-container">
    <a href="Home.html" class="back-icon" title="Go Back">
      <img src="backButton.png" width="20px" height="20px">
    </a>

    <h2>User Registration</h2>
    <?php if (!empty($message)) echo $message; ?>

    <form action="" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" required>
      </div>

      <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" required>
      </div>

      <div class="form-group">
        <label for="contact">Contact No</label>
        <input type="tel" id="contact" name="contact" required>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="username">User Name</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="bloodgroup">Blood Group</label>
        <select id="bloodgroup" name="bloodgroup" required>
          <option value="">Select option</option>
          <optgroup label="Blood Groups">
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
          </optgroup>
        </select>
      </div>

      <div class="form-group">
        <label for="weight">Weight</label>
        <input type="number" id="weight" name="weight" required>
      </div>

      <div class="form-group">
        <label for="province">Province</label>
        <input type="text" id="province" name="province" required>
      </div>

      <div class="form-group">
        <label for="district">District</label>
        <input type="text" id="district" name="district" required>
      </div>

      <div class="form-group">
        <label for="role">Role</label>
        <select name="role" id="role" required>
          <option value="">Select Role</option>
          <option value="viewer">Viewer</option>
        </select>
      </div>

      <div class="form-group">
        <label for="attachment">Medical Report or License</label>
        <input type="file" id="attachment" name="attachment" required>
      </div>

      <div class="form-group checkbox">
        <input type="checkbox" id="conditions" name="conditions" required>
        <label for="conditions">I agree to the <a href="blood_more_info.html">terms and conditions</a>.</label>
      </div>

      <div class="button-group">
        <button type="submit" class="submit-button">Submit</button>
        <a href="Home.html"><button type="button" class="cancel-button">Cancel</button></a>
      </div>
    </form>
  </div>
</body>
</html>
