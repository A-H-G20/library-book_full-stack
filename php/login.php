<?php
session_start();
require 'connection.php'; // Include your database connection

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Protect against SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Fetch user from the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user data in session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Assuming there's a role field in the database

            // Redirect based on the user's role
            if ($user['role'] == 'admin') {
                header('Location: admin/home.html'); // Redirect to admin page
            } elseif ($user['role'] == 'user') {
                header('Location: ../dashboard.html'); // Redirect to user dashboard
            }
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with this email.";
    }
}
?>
