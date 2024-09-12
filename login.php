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

<div class="alert alert-info">Please Enter The Details Below</div>
<div class="login_terry">
    <form method="post" class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="inputEmail">Email</label>
            <div class="controls">
                <input type="email" name="email" placeholder="Email" required>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputPassword">Password</label>
            <div class="controls">
                <input type="password" name="password" placeholder="Password" required>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button name="submit1" type="submit" class="btn btn-info">
                    <i class="icon-signin icon-large"></i>&nbsp;Login
                </button>
            </div>
        </div>
    </form>
</div>
