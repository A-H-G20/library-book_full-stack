<?php
session_start();

// Redirect to login if no session is found
if (!isset($_SESSION['id']) || trim($_SESSION['id']) == '') {
    echo '<script>window.location = "login.php";</script>';
    exit();
}

$session_id = $_SESSION['id'];

require 'connection.php'; // Database connection

// Fetch books with title, image, copies, price, and description from the database
$query = "SELECT id, title, image, copies_available, price, description FROM books";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo 'Error fetching books: ' . mysqli_error($conn);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/dashboard.css" />
    <title>Books</title>
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

    <form action="">
      <div class="products-container">
        <?php while ($book = mysqli_fetch_assoc($result)) { ?>
          <div id="products">
         <!-- Link to book details -->
              <img src="admin/image/<?php echo htmlspecialchars($book['image']); ?>" alt="Book Image" />
              <div>
              <label for="" style="font-weight: bold; color:black"><?php echo htmlspecialchars($book['title']); ?></label><br /><br>

                <label for="count">Copies: <?php echo htmlspecialchars($book['copies_available']); ?></label><br /><br>
                <label for="price">Price: $<?php echo htmlspecialchars($book['price']); ?></label><br /><br><b></b>
                <a href="book_details.php?id=<?php echo $book['id']; ?>" class="button-link">View Details</a>
              </div><br>
             
          </div>
        <?php } ?>
      </div>
    </form>
  </body>
</html>
<?php
// Close the connection
mysqli_close($conn);
