<?php
session_start();

// Redirect to login if no session is found
if (!isset($_SESSION['id']) || trim($_SESSION['id']) == '') {
    echo '<script>window.location = "login.php";</script>';
    exit();
}

$session_id = $_SESSION['id'];

require 'connection.php'; // Database connection

// Fetch reserved books with details from the database
$query = "
    SELECT reserved.id, reserved.book_id, reserved.image, reserved.title, reserved.date_from, reserved.date_to, books.price,reserved.status
    FROM reserved
    INNER JOIN books ON reserved.book_id = books.id
    WHERE reserved.user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $session_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo 'Error fetching reserved books: ' . mysqli_error($conn);
    exit();
}

// Handle delete request
if (isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];

    // Delete the reservation from the database
    $delete_query = "DELETE FROM reserved WHERE id = ? AND user_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, 'ii', $delete_id, $session_id);

    if (mysqli_stmt_execute($delete_stmt)) {
        echo '<script>alert("Reservation deleted successfully!"); window.location.href = "Reserved_Books.php";</script>';
    } else {
        echo 'Error deleting reservation: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css//reserved_book.css" />
    <title>Dashboard Page</title>
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
        <div class="products-container">
            <?php while ($reserved = mysqli_fetch_assoc($result)) { ?>
                <div id="products">
                    <img src="admin/image/<?php echo htmlspecialchars($reserved['image']); ?>" alt="Book Image" />
                    <div>
                        <label for=""style="font-weight: bold; color: black"><?php echo htmlspecialchars($reserved['title']); ?></label><br><br>
                        <label for="">Date From: <?php echo htmlspecialchars($reserved['date_from']); ?></label><br><br>
                        <label for="">Date To: <?php echo htmlspecialchars($reserved['date_to']); ?></label><br><br>
                        <label for="">Status: <?php echo htmlspecialchars($reserved['status']); ?></label><br><br>
                        <label for="">Price: $<?php echo htmlspecialchars($reserved['price']); ?></label><br>
                        <!-- Delete Button Form -->
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($reserved['id']); ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this reservation?');" class="delete-button">Delete</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </form>
</body>
</html>
<?php
// Close the connection
mysqli_close($conn);
?>
