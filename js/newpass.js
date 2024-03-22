const form = document.querySelector(".login form"),
  submitBtn = form.querySelector(".submitButton"),
  errorText = form.querySelector(".error-text");

form.onsubmit = (e) => {
  e.preventDefault();
}

submitBtn.onclick = () => {
  // Disable the submit button
  submitBtn.disabled = true;
  // Change the button text to "Processing..."
  submitBtn.value = "Processing...";
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../backend/newpassword.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (data === "success") {
          location.href = "../pages/dashboard.php";
        } else {
          errorText.style.display = "block";
          errorText.textContent = data;
          // Re-enable the submit button
          submitBtn.disabled = false;
          // Change the button text back to "Register Now"
          submitBtn.value = "Change Password";
          // Hide the error message after 2  seconds
          setTimeout(() => {
            errorText.style.display = "none";
          }, 5000);
        }
      }
    }
  };
  let formData = new FormData(form);
  xhr.send(formData);
}
