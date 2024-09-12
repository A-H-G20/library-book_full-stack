<?php
session_start();
require 'connection.php'; // Ensure $conn is initialized correctly here

if (isset($_POST['submit1'])) {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // Fetch the user from the database
        $query = "SELECT * FROM users WHERE email=?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($user = mysqli_fetch_assoc($result)) {
                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect based on user role
                    if ($user['role'] === 'admin') {
                        header('Location: admin/home.php');
                    } else {
                        header('Location: dashboard.php');
                    }
                    exit();
                } else {
                    echo '<div class="alert alert-danger">Incorrect password.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">No user found with this email.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Database error: Unable to prepare statement.</div>';
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo '<div class="alert alert-danger">Please fill in both email and password.</div>';
    }
}

// Close the connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>login page</title>
</head>
<body>
    


    <form method="post">
    <div class="logo" id="logo">
        <img src="images/logo.png" alt="logo" />
      </div>
      <div class="mssg" id="mssg">
        <h1>Welcome Back</h1>
      </div>
      <div class="frm" id="frm">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Password" required>
      </div>
        <div class="sbmt" id="sbmt">
            <button name="submit1" type="submit">
                Login
            </button>
        </div>
        <div id="sing">
        <p>Already have an account? <a href="signup.php">Login here</a></p>
      </div>
    </form>

</body>
</html>