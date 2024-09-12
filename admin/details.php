<?php
session_start();

// Redirect to login if no session is found
if (!isset($_SESSION['id']) || trim($_SESSION['id']) == '') {
    echo '<script>window.location = "../login.php";</script>';
    exit();
}

require '../connection.php'; // Database connection

// Check if book ID is passed via URL
if (isset($_GET['id'])) {
    $book_id = (int) $_GET['id']; // Convert ID to integer to avoid SQL injection

    // Fetch the book's details from the database
    $query = "SELECT title, image, price, description FROM books WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $book_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book = mysqli_fetch_assoc($result);

    if (!$book) {
        echo 'Book not found!';
        exit();
    }
} else {
    echo 'Invalid book ID!';
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_from = $_POST['date-from'];
    $date_to = $_POST['date-to'];

    // Validate the dates
    if (empty($date_from) || empty($date_to)) {
        echo 'Please select both dates!';
    } else {
        $user_id = $_SESSION['id']; // Assuming user ID is stored in session
        $status = 'reserved'; // Default status

        // Insert reservation into the database
        $query = "INSERT INTO reserved (user_id, book_id, image, title, date_from, date_to, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iisssss', $user_id, $book_id, $book['image'], $book['title'], $date_from, $date_to, $status);
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Book reserved successfully!");</script>';
        } else {
            echo '<script>alert("Error reserving the book!");</script>';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/details.css"> <!-- Link to your CSS file -->
    <title><?php echo htmlspecialchars($book['title']); ?></title>
</head>
<body>
<header class="header-bar">
      <nav>
        <ul>
          <li><a href="home.php">Home</a></li>
          <select class="nav-menu" id="management-select">
            <option selected disabled>Application Management</option>
            <option value="user_managment.php">User Managment</option>
            <option value="book_managment.php">Book Managment</option>
            <option value="reserved_books_managment.php">
              Reserved Books Managment
            </option>
          </select>
          <li><a href="../login.php">Logout</a></li>
        </ul>
      </nav>
    </header>
<div class="card">
    <!-- Navigation to go back -->
    <nav>
        <a href="dashboard.php"><img src="../images/icons/back.png" alt="Back" /></a>
    </nav>
    <!-- Book image section -->
    <div class="photo">
        <img src="image/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" />
    </div>
    <form method="POST">
    <!-- Book details and description -->
    <div class="description">
        <h2><?php echo htmlspecialchars($book['title']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
        <h1>Price: $<?php echo htmlspecialchars($book['price']); ?></h1>
        
        <!-- Reservation Form -->
        
            
        <a href="book_managment.php" class="button-link">Edit</a>
        </form>
    </div>
</div>

<?php
// Close the connection
mysqli_close($conn);
?>
</body>
<script src="../js/managment_selection.js"></script>
</html>
