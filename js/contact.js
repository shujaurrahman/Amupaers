const form = document.querySelector(".login form");
const submitBtn = form.querySelector(".submitButton");
const errorText = form.querySelector(".error-text");

form.onsubmit = (e) => {
  e.preventDefault();
}

submitBtn.onclick = () => {
  // Get form input values
  const name = form.querySelector('[name="name"]').value.trim();
  const email = form.querySelector('[name="email"]').value.trim();
  const subject = form.querySelector('[name="subject"]').value.trim();
  const message = form.querySelector('[name="message"]').value.trim();

  // Validate form inputs
  if (name === "" || email === "" || subject === "" || message === "") {
    alert("Please fill in all required fields.");
    return; // Stop further execution
  }

  // Form data is valid, proceed with form submission
  submitBtn.disabled = true;
  submitBtn.value = "Processing...";

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../backend/contact.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (data === "success") {
          // Optionally handle success response
        } else {
          errorText.style.display = "block";
          errorText.textContent = data;
          submitBtn.disabled = false;
          submitBtn.value = "Submit";
          setTimeout(() => {
            errorText.style.display = "none";
            location.href = "../index.php";
          }, 5000);
        }
      }
    }
  };
  let formData = new FormData(form);
  xhr.send(formData);
};
