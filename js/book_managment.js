document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(".edit-button");
  const addButtons = document.querySelectorAll(".add-button");
  const addFormContainer = document.querySelector(".form-container");
  const editFormContainer = document.querySelector(".edit-form-container");
  const addCancelButton = document.querySelector(".cancel-button");
  const editCancelButton = document.querySelector(".edit-cancel-button");

  // Hide both forms initially
  addFormContainer.style.display = "none";
  editFormContainer.style.display = "none";

  // Add event listeners to edit buttons
  editButtons.forEach((button) => {
    button.addEventListener("click", () => {
      addFormContainer.style.display = "none";
      editFormContainer.style.display = "block";
    });
  });

  // Add event listeners to add buttons
  addButtons.forEach((button) => {
    button.addEventListener("click", () => {
      addFormContainer.style.display = "block";
      editFormContainer.style.display = "none";
    });
  });

  // Add event listener to add form cancel button
  addCancelButton.addEventListener("click", (event) => {
    event.preventDefault(); // Prevent form submission
    addFormContainer.style.display = "none";
  });

  // Add event listener to edit form cancel button
  editCancelButton.addEventListener("click", (event) => {
    event.preventDefault(); // Prevent form submission
    editFormContainer.style.display = "none";
  });
});
document
  .getElementById("management-select")
  .addEventListener("change", function () {
    const selectedPage = this.value;
    if (selectedPage) {
      window.location.href = selectedPage;
    }
  });
