<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .dashboard-container {
      text-align: center;
      color: white;
    }

    .dashboard-container h1 {
      font-size: 32px;
      margin-bottom: 20px;
    }

    .dashboard-container .btn {
      width: 150px;
      background-color: crimson;
      margin-top: 20px;
    }

    .dashboard-container .btn:hover {
      background-color: darkred;
    }
  </style>
</head>
<body>
  <div class="wrapper dashboard-container">
    <h1>Welcome, <?= htmlspecialchars($_SESSION["username"]) ?>!</h1>
    <p>You have successfully logged in.</p>

    <form action="logout.php" method="post">
      <button type="submit" class="btn">Logout</button>
    </form>
  </div>
</body>
</html>
