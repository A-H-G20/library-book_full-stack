document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(
    ".user-list table button:first-child"
  );
  const formContainer = document.querySelector(".container.form-container");
  const cancelButton = document.querySelector(
    ".form-group button.cancel-button"
  );

  // Hide form initially
  formContainer.style.display = "none";

  // Add event listeners to edit buttons
  editButtons.forEach((button) => {
    button.addEventListener("click", () => {
      formContainer.style.display = "block";
    });
  });

  // Add event listener to cancel button
  if (cancelButton) {
    cancelButton.addEventListener("click", (event) => {
      event.preventDefault(); // Prevent form submission
      formContainer.style.display = "none";
    });
  }
});

document
  .getElementById("management-select")
  .addEventListener("change", function () {
    const selectedPage = this.value;
    if (selectedPage) {
      window.location.href = selectedPage;
    }
  });
