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
  xhr.open("POST", "../backend/verify.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (data === "success") {
            location.href = "../pages/dashboard.php";
        } else if (data === "newpassword") {
            location.href = "../pages/new-pass.php"; 
        } else {
          errorText.style.display = "block";
          errorText.textContent = data;
          // Re-enable the submit button
          submitBtn.disabled = false;
          submitBtn.value = "verify code";
          // Hide the error message after 2 seconds
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

window.addEventListener('DOMContentLoaded', () => {
  const msgText = document.querySelector('.msg-text');
  if (msgText.textContent.trim() !== '') {
      msgText.style.display = 'block'; // Display the element if it has content

      // Set a timer to hide the message after 5 seconds
      setTimeout(() => {
          msgText.style.display = 'none';
      }, 5000); // 5000 milliseconds = 5 seconds
  }
});