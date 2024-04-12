document.addEventListener("DOMContentLoaded", function() {
    const departmentSelect = document.getElementById("department-select");
    const courseSelect = document.getElementById("course-select");

    // Fetch departments based on the category on page load
    fetchDepartments();

    function fetchDepartments() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `../backend/fetch_departments.php?category=<?php echo $category; ?>`, true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Request was successful
                // Parse the response as JSON
                const response = JSON.parse(xhr.responseText);
                // Update the departments dropdown
                updateDepartmentsDropdown(response.departments);
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
    }

    function updateDepartmentsDropdown(departments) {
        // Clear existing options
        departmentSelect.innerHTML = '<option value="">Select Department</option>';
        // Add new options
        departments.forEach(function(department) {
            const option = document.createElement("option");
            option.value = department;
            option.textContent = department;
            departmentSelect.appendChild(option);
        });
    }

    departmentSelect.addEventListener("change", function() {
        const selectedDepartment = this.value;
        if (selectedDepartment) {
            // Fetch courses based on the selected department
            fetchCourses(selectedDepartment);
        } else {
            // If no department is selected, reset the courses dropdown
            resetDropdown(courseSelect);
        }
    });

    function fetchCourses(department) {
        // AJAX request to fetch courses based on the selected department
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `../backend/fetch_courses.php?department=${department}&category=<?php echo $category; ?>`, true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Request was successful
                // Parse the response as JSON
                const response = JSON.parse(xhr.responseText);
                // Update the courses dropdown
                updateCoursesDropdown(response.courses);
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
    }


    function updateCoursesDropdown(courses) {
        // Clear existing options
        courseSelect.innerHTML = '<option value="">Select Course</option>';
        // Add new options
        courses.forEach(function(course) {
            const option = document.createElement("option");
            option.value = course;
            option.textContent = course;
            courseSelect.appendChild(option);
        });
    }

    function resetDropdown(dropdown) {
        // Reset the given dropdown by removing all options except the default one
        while (dropdown.options.length > 1) {
            dropdown.remove(1);
        }
    }
});

function openPDF(filePath, paperId) {
    // Open the PDF file in a new tab
    window.open(filePath, '_blank');

    // Send AJAX request to update view count
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../backend/update_view_count.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Request was successful
                // console.log("View count updated successfully.");
            } else {
                // Error handling
                console.error("Error updating view count:", xhr.statusText);
            }
        }
    };
    xhr.send(`paper_id=${paperId}`);
}

  
function sortPapers() {
    const sortBy = document.getElementById("sort-papers").value;
    const department = document.getElementById("department-select").value;
    const course = document.getElementById("course-select").value;

    // Send AJAX request to fetch sorted papers
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `../backend/sort_papers.php?category=<?php echo $category; ?>&department=${department}&course=${course}&sort=${sortBy}`, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Request was successful
            // Parse the response as JSON
            const response = JSON.parse(xhr.responseText);
            // Update the UI with sorted papers
            updatePapersUI(response.papers);
            // Display success message
            const errorText = document.querySelector('.error-text');
            errorText.textContent = `Papers sorted in ${sortBy} format.`;
            errorText.style.display = 'block';
            // Hide the error text after 5 seconds
            setTimeout(function() {
                errorText.style.display = 'none';
            }, 5000);
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
}


function updatePapersUI(papers) {
    const papersContainer = document.getElementById("papers-container");
    // Clear existing papers
    papersContainer.innerHTML = "";
    // Append sorted papers to the container
    papers.forEach(function(paper) {
        // Convert the upload date string to a Date object
        const uploadDate = new Date(paper['upload_date']);
        // Get the month name
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        const month = monthNames[uploadDate.getMonth()];
        // Format the date in the desired format
        const formattedDate = `${month} ${uploadDate.getDate()}, ${uploadDate.getFullYear()}`;

        const li = document.createElement("li");
        li.innerHTML = `
            <div class='category-card' onclick='openPDF(\"${paper['file_path']}\", ${paper['id']})' title='Click to view paper'>
                <div>
                    <h3 class='h3 card-title'>
                        <a href='#'>${paper['title']}</a>
                    </h3>
                    <span class='card-meta'>${paper['course']}</span><br>
                    <span class='card-meta'>Year: ${paper['year']}</span><br>
                    <span class='card-meta'>Added on: ${formattedDate}</span>
                </div>
            </div>
        `;
        papersContainer.appendChild(li);
    });
}