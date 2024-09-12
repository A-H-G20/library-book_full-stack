document.addEventListener('DOMContentLoaded', function() {
    // Select all buttons with the class 'view-details-btn'
    const buttons = document.querySelectorAll('.view-details-btn');

    // Loop through all the buttons and attach a click event listener
    buttons.forEach(button => {
      button.addEventListener('click', function() {
        // Get the book ID from the button's data attribute
        const bookId = this.getAttribute('data-id');
        
        // Redirect to the book details page with the corresponding ID
        window.location.href = `../book_details.php?id=${bookId}`;
      });
    });
  });