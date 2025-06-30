<?php
session_start();

// Database connection
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "user_db";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Prepare SQL to avoid SQL injection
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) {
        // Bind result
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Password is correct
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            header("Location: dashboard.php"); // Redirect to dashboard or home page
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Result</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="wrapper">
    <h1>Login Result</h1>
    <?php if (isset($error)): ?>
      <p style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></p>
      <div style="text-align: center;">
        <a href="index.html" style="color: white;">Go back to login</a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
