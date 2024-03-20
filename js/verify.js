const form = document.querySelector(".login form"); 
const submitBtn = form.querySelector(".submitButton");
const errorText = form.querySelector(".error-text");

form.addEventListener("submit", (e) => {
  e.preventDefault(); // Prevent default form submission
  const formData = new FormData(form);
  
  // Send AJAX request
  fetch("../backend/verify.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    if (data.trim() === "success") {
      location.href = "../pages/dashboard.php";
    } else {
      errorText.style.display = "block";
      errorText.textContent = data;
      setTimeout(() => {
        errorText.style.display = "none";
      }, 2000);
    }
  })
  .catch(error => {
    console.error("Error:", error);
  });
});
