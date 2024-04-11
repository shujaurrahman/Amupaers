<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AMU Papers</title>
  <?php require_once "../assets/fonts.php"?>
  <!-- custom css link -->
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/form.css">
  <style>
    .small {
    font-size: 12px; /* Adjust the font size as needed */
    margin: 0;
    
}
.section{
    padding-top: 20px;
}
body{
    min-height: 120vh;
}
.btn {
    min-height: 48px;
    max-width: max-content;
    font-size: var(--fs-4);
    font-weight: var(--fw-500);
    display: flex;
    align-items: center;
    gap: 1px;
    padding: 8px 14px;
    border-radius: var(--radius-6);
    transition: var(--transition-1);
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
        <ul class="grid-list">
            <?php
            require_once '../backend/error.php'; 
            require_once '../classes/database.php'; 
            require_once '../classes/paper.php'; 
            $database = new Database();
            $conn = $database->getConnection();
            $paper = new Paper($conn);
            // Fetch popular papers
            $papers = $paper->getPopularPapersByViews(15);

            if (!empty($papers)) {
                foreach ($papers as $paper) {
                  $uploadDate = date("F j, Y", strtotime($paper['upload_date']));
                      echo "<li>
                      <div class='category-card'  onclick='openPDF(\"{$paper['file_path']}\", {$paper['id']})' title='Click to view paper'>
                        <div>
                            <h3 class='h3 card-title'>
                                <a href='#'>{$paper['title']}</a>
                            </h3>
                            <span class='card-meta '>{$paper['department']} DEPARTMENT </span><br>
                            <span class='card-meta small'>{$paper['course']}</span> <br>
                            <span class='card-meta small'>{$paper['category']} paper of year {$paper['year']}</span><br>
                            <span class='card-meta small'>Added {$uploadDate} |</span> 
                            <span class='card-meta small'>Views: {$paper['view_count']}</span><br>
                           
                            
                        </div>
                    </div>
                </li>";
                    
                }
            } else {
                // If no popular papers found
                echo "<p>No popular papers found.</p>";
            }
            ?>
            
          </ul>
          <a href="login.php" class="btn btn-primary" style="margin-top:20px !important;">
            <span class="span">Login to view all</span>
            <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
          </a>
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

    </script>
    <script src="../js/script.js" defer></script>
</body>

</html>