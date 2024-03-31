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
  <style>
    .small {
    font-size: 12px; /* Adjust the font size as needed */
    margin: 0;
    
}
.section{
    padding-top: 110px;
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

            // Check if there are any popular papers
            if (!empty($papers)) {
                foreach ($papers as $papers) {
                  $uploadDate = date("F j, Y", strtotime($papers['upload_date']));
                      echo "<li>
                      <div class='category-card'  onclick='openPDF(\"{$papers['file_path']}\", {$papers['id']})' title='Click to view paper'>
                        <div>
                            <h3 class='h3 card-title'>
                                <a href='#'>{$papers['title']}</a>
                            </h3>
                            <span class='card-meta '>{$papers['department']} DEPARTMENT </span><br>
                            <span class='card-meta small'>{$papers['course']}</span> <br>
                            <span class='card-meta small'>{$papers['category']} paper of year {$papers['year']}</span><br>
                            <span class='card-meta small'>Added {$uploadDate} |</span> 
                            <span class='card-meta small'>Views: {$papers['view_count']}</span><br>
                           
                            
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
</body>

</html>