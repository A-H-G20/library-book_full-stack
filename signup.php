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
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/signup.css" />
    <title>Signup page</title>
  </head>
  <body>
    <form action="" method="POST">
      <div class="logo" id="logo">
        <img src="images/logo.png" alt="logo" />
      </div>
      <div class="mssg" id="mssg">
        <h1>Register now</h1>
      </div>
      <div class="frm" id="frm">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required /><br /><br />
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required /><br /><br />
        <label for="pass">Password:</label>
        <input type="password" id="pass" name="password" required />
      </div>
      <div id="sbmt" class="sbmt">
        <button type="submit">Signup</button>
      </div>
      <div id="sing">
        <p>Already have an account? <a href="login.php">Login here</a></p>
      </div>
    </form>
  </body>
</html>
