<?php
// require_once "../backend/error.php";
require_once "../classes/paper.php";
require_once "../classes/Database.php";

session_start(); 

// Check if the user is logged in and has the role of "super_admin"
if (isset($_SESSION['role']) && $_SESSION['role'] === 'super admin') {
    $isSuperAdmin = true;
} else {
    $isSuperAdmin = false;
    header("location: ../index.php");
}

// Initialize Database connection
$database = new Database();
$conn = $database->getConnection();

// Initialize User object
$paper = new Paper($conn);

// Fetch all papers
$papers = $paper->getAllPapers();

// Check if there are any papers
if (!empty($papers)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="wi.dth=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="./css/admindash.css">
        <?php require_once "../assets/fonts.php"; ?>
        <style>
            .btn-group .btn {
  margin-right: 10px; /* Adjust the value as needed */
}
.btn {
    min-height: 25px;
    max-width: max-content;
    font-size: var(--fs-5);
    font-weight: var(--fw-700);
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 15px;
    border-radius: var(--radius-6);
    transition: var(--transition-1);
    margin-bottom: 12px;
    
}
.btn:hover{
    background-color: var(--green);
    color: white;
}
.ion-icon-container:hover {
    color: var(--ultramarine-blue);
}
a:hover{
    color: var(--ultramarine-blue);

}
        </style>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </head>
    <body>
    <?php require_once "../includes/adminNav.php"; ?>
    <br><br><br><br>
    <div class="container">
    <div class="btn-group" role="group" aria-label="Basic outlined example">
  <a href='#' class='btn header-action-btn login-btn' style=''>Pending</a>
  <a href='#' class='btn header-action-btn login-btn' style=''>Approved</a>
  <a href='#' class='btn header-action-btn login-btn' style=''>Rejected</a>
</div>

        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Department</th>
                                    <th>Course</th>
                                    <th>View</th>
                                    <th>Year</th>
                                    <th>Upload Date</th>
                                    <th>Uploaded By</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Loop through each paper and display its details
                                foreach ($papers as $paper) {
                                    echo "<tr>";
                                    echo "<td>{$paper['id']}</td>";
                                    echo "<td>{$paper['title']}</td>";
                                    echo "<td>{$paper['category']}</td>";
                                    echo "<td>{$paper['department']}</td>";
                                    echo "<td>{$paper['course']}</td>";
                                    echo "<td><span class='ion-icon-container'><a href='{$paper['file_path']}' target='_blank'><ion-icon name='eye' size='large'></ion-icon></a></span></td>";
                                    echo "<td>{$paper['year']}</td>";
                                    $uploadDate = date('d F Y', strtotime($paper['upload_date']));
                                    echo "<td>{$uploadDate}</td>";
                                    echo "<td>{$paper['uploaded_by']}</td>";
                                    echo "<td>";
                                    echo "<span class='ion-icon-container'><ion-icon name='checkmark-circle' size='large'></ion-icon></span>";
                                    echo "<span class='ion-icon-container'><ion-icon name='close' size='large'></ion-icon></span></td>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    <script>
  // Function to handle button clicks
  function fetchPapersByStatus(status) {
    // Send AJAX request to fetch papers with the selected status
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `../backend/fetch_papers_by_status.php?status=${status}`, true);
    xhr.onload = function() {
      if (xhr.status >= 200 && xhr.status < 300) {
        // Request was successful
        // Parse the response as JSON
        const papers = JSON.parse(xhr.responseText);
        // Update the table with the fetched papers
        updateTable(papers);
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

  function updateTable(papers) {
    const tbody = document.querySelector("tbody");
    tbody.innerHTML = ""; // Clear existing table rows
    // Loop through the fetched papers and create table rows
    papers.forEach(function(paper) {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${paper.id}</td>
            <td>${paper.title}</td>
            <td>${paper.category}</td>
            <td>${paper.department}</td>
            <td>${paper.course}</td>
            <td><span class='ion-icon-container'><a href="${paper.file_path}" target="_blank"><ion-icon name='eye' size='large'></ion-icon></a></span></td>
            <td>${paper.year}</td>
            <td>${paper.upload_date}</td>
            <td>${paper.uploaded_by}</td>
            <td>${getActionIcon(paper.status)}</td>
        `;
        tbody.appendChild(tr);
    });
}

// Function to get the appropriate action icon based on paper status
function getActionIcon(status) {
    switch(status) {
        case 'pending':
            return `<td><span class='ion-icon-container'><ion-icon name='checkmark-circle' size='large'></ion-icon></span><span class='ion-icon-container'><ion-icon name='close' size='large'></ion-icon></span></td>`;
        case 'approved':
            return `<td><span class='ion-icon-container'><ion-icon name='create' size='large'></ion-icon></span><span class='ion-icon-container'><ion-icon name='trash' size='large'></ion-icon></span></td>`;
        case 'rejected':
            return `<span class='ion-icon-container'><ion-icon name='trash' size='large'></ion-icon></span>`;
        default:
            return '';
    }
}

  // Add event listeners to the buttons
  document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.querySelectorAll(".header-action-btn");
    buttons.forEach(function(button) {
      button.addEventListener("click", function() {
        const status = this.textContent.trim(); // Get the status from the button text
        fetchPapersByStatus(status); // Fetch papers with the selected status
      });
    });
  });
</script>

    </html>
    <?php
} else {
    // If no papers found
    echo "<p>No papers found.</p>";
}
?>
