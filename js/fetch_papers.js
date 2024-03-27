
  document.addEventListener("DOMContentLoaded", function() {
    const goButton = document.querySelector(".go-button");
    const papersContainer = document.getElementById("papers-container");
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category');

    // Add click event listener to the "Go" button
    goButton.addEventListener("click", function() {
      // Gather selected filter values
      const department = document.getElementById("department-select").value;
      const course = document.getElementById("course-select").value;

      // Send AJAX request
      const xhr = new XMLHttpRequest();
      xhr.open("GET", `../backend/fetch_papers.php?category=${category}&department=${department}&course=${course}`, true);
      xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
          // Request was successful
          // Update papers container with fetched papers
          papersContainer.innerHTML = xhr.responseText;
        } else {
          // Error handling
          console.error("Request failed:", xhr.statusText);
        }
      };
      xhr.onerror = function() {
        // Error handling
        console.error("Request failed.");
      };
      xhr.send();
    });
  });