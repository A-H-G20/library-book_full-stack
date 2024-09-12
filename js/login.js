function validateLogin() {
  var email = document.getElementById("email").value;
  var password = document.getElementById("pass").value;

  // Check if the credentials are correct
  if (email === "user@gmail.com" && password === "user") {
    window.location.href = "dashboard.html";
  } else if (email === "admin@gmail.com" && password === "admin") {
    window.location.href = "admin/home.html";
  } else {
    alert("Incorrect email or password.");
  }
  return false;
}
