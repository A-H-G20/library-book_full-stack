<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/book_managment.css" />
    <title>Book Management</title>
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
    <div class="container">
      <div class="main-content">
        <section class="book-list">
          <h2>Books</h2>
          <br />
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Copies</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <button class="add-button">Add</button>
              <button class="edit-button">Edit</button>
              <button class="delete-button">Delete</button>
            </tbody>
          </table>
        </section>

        <div class="form-container">
          <h2>Add Book</h2>
          <br />
          <form action="" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                  <label for="title">Book Name</label>
                  <input type="text" id="title" name="title" required />
              </div>
              <div class="form-group">
                  <label for="image">Image</label>
                  <input type="file" id="image" name="image" accept="image/*" required />
              </div>
              <div class="form-group">
                  <label for="copies">Copies Count</label>
                  <input type="number" id="copies" name="copies" required />
              </div>
              <div class="form-group">
                  <label for="price">Price</label>
                  <input type="text" id="price" name="price" required />
              </div>
              <div class="form-group">
                  <label for="description">Description</label>
                  <textarea id="description" name="description" rows="4" required></textarea>
              </div>
              <div class="form-group">
                  <button type="submit">Add</button>
                  <button type="button" class="cancel-button">Cancel</button>
              </div>
          </form>
      </div>
      

        <div class="edit-form-container" style="display: none">
          <h2>Edit Book</h2>
          <br />
          <form>
            <div class="form-group">
              <label for="edit-title">Name</label>
              <input type="text" id="edit-title" required />
            </div>
            <div class="form-group">
              <label for="edit-image">Image</label>
              <input type="file" id="edit-image" required />
            </div>
            <div class="form-group">
              <label for="edit-copies">Copies count</label>
              <input type="text" id="edit-copies" required />
            </div>
            <div class="form-group">
              <button type="submit">Edit</button>
              <button type="button" class="edit-cancel-button">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="../js/book_managment.js"></script>
    <script src="../js/managment_selection.js"></script>
  </body>
</html>

<?php
require '../connection.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $copies = (int) $_POST['copies'];
    $price = (float) $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $uploadDir = 'image/'; // Folder where images are stored
        $imagePath = $uploadDir . $imageName;

        // Move uploaded file to the target directory
        if (move_uploaded_file($imageTmpPath, $imagePath)) {
            // Insert data into the database
            $query = "INSERT INTO books (title, image, copies_available, price, description) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'ssids', $title, $imageName, $copies, $price, $description);
                if (mysqli_stmt_execute($stmt)) {
                    echo "Book added successfully!";
                } else {
                    echo "Error: Could not execute the query.";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Error: Could not prepare the query.";
            }
        } else {
            echo "Error: Could not move uploaded file.";
        }
    } else {
        echo "Error: No file uploaded or there was an upload error.";
    }

    // Close the connection
    mysqli_close($conn);
}
?>
