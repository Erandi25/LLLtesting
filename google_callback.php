<?php
if (isset($_GET['access_token'])) {
    $accessToken = $_GET['access_token'];

    // Fetch user info from Google
    $url = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $accessToken;
    $response = file_get_contents($url);
    $userInfo = json_decode($response, true);

    if (isset($userInfo['email'])) {
        // Database connection
        $host = 'localhost';
        $db_username = 'root';
        $password = '';
        $database = 'lifelinelocator';

        $conn = new mysqli($host, $db_username, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if user already exists
        $email = $userInfo['email'];
        $googleId = $userInfo['id'];
        $name = $userInfo['name'];

        $stmt = $conn->prepare("SELECT * FROM signin WHERE email = ? AND google_id = ?");
        $stmt->bind_param("ss", $email, $googleId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User exists, log them in
            header("Location: NewHome.html");
            exit();
        } else {
            // Insert new user into database
            $stmt = $conn->prepare("INSERT INTO signin (username, email, google_id, login_type) VALUES (?, ?, ?, ?)");
            $loginType = "google";
            $stmt->bind_param("ssss", $name, $email, $googleId, $loginType);

            if ($stmt->execute()) {
                echo "Google login successful!";
                header("Location: NewHome.html");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Failed to fetch user info.";
    }
} else {
    echo "Access token not provided.";
}
?>
