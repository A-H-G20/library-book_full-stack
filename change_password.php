<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/change_password.css" />
    <title>Document</title>
  </head>
  <body>
    <header class="header-bar">
      <nav>
        <ul>
        <li><a href="dashboard.php">Home</a></li>
          <li><a href="Reserved_Books.php">Reserved Books</a></li>
          <li><a href="change_password.php">Settings</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </header>

    <form action="" method="post">
    <div id="name">
        <h1>Change Password</h1>
    </div>
    <div class="frm">
        <label for="oldpass">Old Password:</label>
        <input type="password" name="oldpass" id="oldpass" required /><br /><br />
        <label for="newpass">New Password:</label>
        <input type="password" name="newpass" id="newpass" required /><br /><br />
        <label for="confirmpass">Confirm Password:</label>
        <input type="password" name="confirmpass" id="confirmpass" required />
    </div>
    <div class="btn">
        <button type="submit">Submit</button>
        <button type="reset">Cancel</button>
    </div>
</form>
  </body>
</html>
<?php

session_start();
include('connection.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['id']; // Get user ID from session

    // Get form inputs
    $oldpass = $_POST['oldpass'];
    $newpass = $_POST['newpass'];
    $confirmpass = $_POST['confirmpass'];

    // Validate passwords
    if (empty($oldpass) || empty($newpass) || empty($confirmpass)) {
        echo 'All fields are required!';
        exit;
    }

    if ($newpass !== $confirmpass) {
        echo 'New password and confirm password do not match!';
        exit;
    }

    // Fetch current password from the database
    $query = "SELECT password FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $current_password);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Check if the old password is correct
    if (!password_verify($oldpass, $current_password)) {
        echo 'Old password is incorrect!';
        exit;
    }

    // Hash the new password
    $hashed_newpass = password_hash($newpass, PASSWORD_BCRYPT);

    // Update the new password in the database
    $query = "UPDATE users SET password = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $hashed_newpass, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        echo '<script>alert("Password changed successfully!");</script>';
    } else {
        echo '<script>alert("Error changing password!");</script>';
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
