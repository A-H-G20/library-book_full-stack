<?php
session_start();
require 'connection.php'; // Include your database connection

// Function to generate a random username
function generateUsername($name) {
    // Generate a random number between 100 and 999 to append to the name
    $randomNumber = rand(100, 999);
    // Create a username by combining the name with the random number
    $username = strtolower(str_replace(' ', '', $name)) . $randomNumber;
    return $username;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Protect against SQL injection
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if email already exists
    $emailCheck = "SELECT * FROM users WHERE email = '$email'";
    $emailResult = mysqli_query($conn, $emailCheck);
    
    if (mysqli_num_rows($emailResult) > 0) {
        echo "Email already registered.";
    } else {
        // Generate a hashed password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate a username
        $username = generateUsername($name);
        
        // Insert new user into the database
        $sql = "INSERT INTO users (name, email, username, password,role) VALUES ('$name', '$email', '$username', '$hashedPassword','user')";
        
        if (mysqli_query($conn, $sql)) {
            // Start a session and store user data
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;

            // Redirect to the dashboard or home page
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
