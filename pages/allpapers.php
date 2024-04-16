<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMU Papers</title>
    <?php require_once "../assets/fonts.php" ?>
    <!-- custom css link -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/form.css">
    <style>
        .small {
            font-size: 12px;
            /* Adjust the font size as needed */
            margin: 0;

        }

        .section {
            padding-top: 100px;
        }

        body {
            min-height: 120vh;
        }

        .btn {
            min-height: 40px;
            max-width: max-content;
            font-size: var(--fs-7);
            font-weight: var(--fw-600);
            display: flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: var(--radius-6);
            transition: var(--transition-1);
        }

        .btn-primary{
            background-color: var(--green);
        }
        .btn-primary:hover{
            background-color: var(--ultramarine-blue);
        }
    </style>
</head>

<body id="top">
    <?php
    require_once "../includes/header.php";
    ?>
    <main>
        <article>


            <section class="section category" aria-label="category">
                <div class="container">
                    <p class="section-subtitle">click to open </p>
                    <h2 class="h2 section-title">Popular Papers</h2>

                    <!-- Testing new feature -->
                    <div id="papers-container" class="grid-list">
                        <!-- Papers will be dynamically added here -->
                    </div>

                    <button id="load-more-btn" class="btn btn-primary" style="display: none;margin-top:20px !important;">Load More</button>

                    <!-- <a href="./pages/allpapers.php" class="btn btn-primary" style="margin-top:20px !important;">
                        <span class="span">Explore More</span>
                        <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                    </a> -->

                </div>
            </section>



        </article>
    </main>

    <script>
        function openPDF(filePath, paperId) {
            // Open the PDF file in a new tab
            window.open(filePath, '_blank');

            // Send AJAX request to update view count
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../backend/update_view_count.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
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

        // Function to load more papers
        function loadMorePapers() {
            const container = document.getElementById('papers-container');
            const existingPapersCount = container.children.length;
            const papersToAdd = 6; // Number of papers to add each time

            // Send AJAX request to fetch more papers from the server
            fetch(`../backend/load_more_papers.php?offset=${existingPapersCount}&limit=${papersToAdd}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(paper => {
                            // Create a new HTML element for each paper and append it to the container
                            const listItem = document.createElement('li');
                            listItem.innerHTML = `
                        <div class="category-card" onclick="openPDF('${paper.file_path}', ${paper.id})" title="Click to view paper">
                            <div>
                                <h3 class="h3 card-title">
                                    <a href="#">${paper.title}</a>
                                </h3>
                                <span class="card-meta">${paper.department} DEPARTMENT</span><br>
                                <span class="card-meta small">${paper.course}</span><br>
                                <span class="card-meta small">${paper.category} paper of year ${paper.year}</span><br>
                                <span class="card-meta small">Added ${paper.uploadDate} | Views: ${paper.viewCount}</span>
                            </div>
                        </div>
                    `;
                            container.appendChild(listItem);
                        });

                        // If there are more papers to load, show the "Load More" button
                        if (data.length === papersToAdd) {
                            document.getElementById('load-more-btn').style.display = 'block';
                        } else {
                            document.getElementById('load-more-btn').style.display = 'none';
                        }
                    }
                })
                .catch(error => console.error('Error fetching papers:', error));
        }

        // Load initial papers and show "Load More" button when the window is loaded
        window.onload = function () {
            loadMorePapers();
            document.getElementById('load-more-btn').addEventListener('click', loadMorePapers);
        };



    </script>
    <script src="../js/script.js" defer></script>
</body>

</html>