<?php
// require_once "../backend/error.php";
require_once "../classes/paper.php";
require_once "../classes/Database.php";

session_start();

// Check if the user is logged in and has the role of "super_admin"
if (isset($_SESSION['role']) && $_SESSION['role'] === 'super admin') {
    $isSuperAdmin = true;
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $isAdmin = true;
} else {
    $isSuperAdmin = false;
    $isAdmin = false;
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
// if (empty($papers)) {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="wi.dth=device-width, initial-scale=1.0">
    <title>Action Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="./css/admindash.css">
    <?php require_once "../assets/fonts.php"; ?>
    <style>
        tr {
            border: 2px solid !important;
            border-color: var(--green) !important;
        }

        td,
        th {
            padding-left: 10px !important;

        }

        .btn-group .btn {
            margin-right: 10px;
            /* Adjust the value as needed */
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

        .btn:hover {
            background-color: var(--green);
            color: white;
        }

        .btn.active {
            background-color: var(--green);
            color: white;
        }

        span a:hover,
        .ion-icon-container:hover {
            color: var(--ultramarine-blue);
        }

        .error-text {
            color: #fff;
            /* color: var(--ultramarine-blue); */
            padding: 5px 15px;
            text-align: center;
            border-radius: 6px;
            background: var(--ultramarine-blue);
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
            display: none;
            width: 50rem;
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
            <a href='#' class='btn header-action-btn login-btn active'>Pending</a>
            <a href='#' class='btn header-action-btn login-btn' style=''>Approved</a>
            <a href='#' class='btn header-action-btn login-btn' style=''>Rejected</a>

        </div>

        <div class="container-fluid p-0">
            <p class="copyright" style="padding:20px 30px;">Click on button to fetch paper data.</p>
            <div class="error-text"></div>

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
        xhr.onload = function () {
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
        xhr.onerror = function () {
            // Error handling
            console.error("Request failed.");
        };
        xhr.send();
    }

    function updateTable(papers) {
        const tbody = document.querySelector("tbody");
        tbody.innerHTML = ""; // Clear existing table rows
        // Loop through the fetched papers and create table rows
        papers.forEach(function (paper) {
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
                <td>${getActionIcon(paper.status, paper.id)}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Function to get the appropriate action icon based on paper status
    function getActionIcon(status, paperId) {
        switch (status) {
            case 'pending':
                return `<span class='ion-icon-container' onclick='updateStatus("${paperId}", "approved")'><ion-icon name='checkmark-circle' size='large' ></ion-icon></span><span class='ion-icon-container' onclick='updateStatus("${paperId}", "rejected")'><ion-icon name='close' size='large'></ion-icon></span>`;
            case 'approved':
                return `<span class='ion-icon-container'><ion-icon name='create' size='large'></ion-icon></span><span class='ion-icon-container'><ion-icon name='trash' size='large' onclick='updateStatus("${paperId}", "rejected")'></ion-icon></span>`;
            case 'rejected':
                return `<span class='ion-icon-container'><ion-icon name='trash' size='large'></ion-icon></span>`;
            default:
                return '';
        }
    }

    // Function to update paper status
    function updateStatus(paperId, newStatus) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `../backend/update_paper_status.php?paper_id=${paperId}&status=${newStatus}`, true);
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {

                // console.log(xhr.responseText);
                // Check if the update was successful
                const response = JSON.parse(xhr.responseText);
                // console.log("Response:", response);
                if (response.success) {
                    // Display success message
                    displayMessage(response.message);
                    // Delay reload after displaying the message
                    setTimeout(function () {
                        location.reload();
                    }, 4000); // Adjust the delay time as needed
                } else {
                    console.error("Error updating status:", response.error);
                }
            } else {
                // Error handling
                console.error("Request failed:", xhr.statusText);
            }
        };
        xhr.onerror = function () {
            // Error handling
            console.error("Request failed.");
        };
        xhr.send();
    }


    // Display message in error-text div
    function displayMessage(message) {
        const errorText = document.querySelector('.error-text');
        errorText.innerText = message;
        errorText.style.display = 'block'; // Make the error text visible
        setTimeout(function () {
            errorText.style.display = 'none'; // Hide the error text after 5 seconds
        }, 5000);
    }


    // Add event listeners to the buttons
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll(".header-action-btn");
        buttons.forEach(function (button) {
            button.addEventListener("click", function () {
                const status = this.textContent.trim(); 
                fetchPapersByStatus(status);
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Simulate click event on the "Pending" button
        document.querySelector(".header-action-btn.active").click();
    });
</script>

</html>