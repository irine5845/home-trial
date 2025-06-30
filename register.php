<?php
session_start();

// Connect to DB
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "user_db";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (!empty($username) && !empty($password)) {
        // Check if user exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Username already exists!";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);
            if ($stmt->execute()) {
                $message = "Registration successful! <a href='index.html'>Login now</a>";
            } else {
                $message = "Error during registration.";
            }
            $stmt->close();
        }
        $check->close();
    } else {
        $message = "Please fill in both fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .wrapper p.message {
      text-align: center;
      color: yellow;
      font-weight: bold;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <form method="POST" action="register.php">
      <h1>Register</h1>

      <div class="input-box">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" placeholder="Enter username" required><br><br>
        <i class='bx bxs-user'></i>
      </div>

      <div class="input-box">
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Enter password" required><br><br>
        <i class='bx bxs-lock-alt'></i>
      </div>

      <button type="submit" class="btn">Register</button>

      <div class="register-link">
        <p>Already have an account? <a href="index.html">Login</a></p>
      </div>

      <?php if (!empty($message)): ?>
        <p class="message"><?= $message ?></p>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>
