<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AMU Papers</title>
  <?php require_once "assets/fonts.php"?>
  <!-- custom css link -->
  <link rel="stylesheet" href="./css/style.css">
  <style>
    .small {
    font-size: 12px; /* Adjust the font size as needed */
    margin: 0;
    
}


  </style>
</head>

<body id="top">
  <?php
  require_once "includes/header.php";
  ?>
  <main>
    <article>

      <!--- #HERO-->
      <section class="hero" id="home" aria-label="hero" style="background-image: url('./assets/images/hero-bg.jpg')">
        <div class="container">

          <div class="hero-content">

            <p class="section-subtitle">Better Learning With Us</p>

            <h2 class="h1 hero-title">Education Is About Academic Excellence</h2>

            <p class="hero-text">
              Excel your exams by practising the questions asked before in entrance,semester or sessionals of Aligarh Muslim University.
            </p>

            <a href="http://localhost/amupapers/pages/register.php" class="btn btn-primary">
              <span class="span">Register Now</span>

              <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
            </a>

          </div>

        </div>
      </section>





      <!--- #Papers-->

      <section class="section category" aria-label="category">
    <div class="container">
        <p class="section-subtitle">click to open </p>
        <h2 class="h2 section-title">Popular Papers</h2>
        <ul class="grid-list">
            <?php
            require_once './classes/database.php'; 
            require_once './classes/paper.php'; 
            $database = new Database();
            $conn = $database->getConnection();
            $paper = new Paper($conn);
            // Fetch popular papers
            $papers = $paper->getPopularPapersByViews(6);

            // Check if there are any popular papers
            if (!empty($papers)) {
                foreach ($papers as $papers) {
                  $uploadDate = date("F j, Y", strtotime($papers['upload_date']));
                  $filePathFromDB=$papers['file_path'];
                  $cleanedFilePath = str_replace('../', '', $filePathFromDB);
                      echo "<li>
                      <div class='category-card'  onclick='openPDF(\"{$cleanedFilePath}\", {$papers['id']})' title='Click to view paper'>
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
            $total_departments = $paper->getTotalDepartments();
            $total_courses = $paper->getTotalCourses();
            $total_papers = $paper->getTotalPapers();
            // echo $total_courses,$total_departments,$total_papers;
            ?>
            
          </ul>
          <a href="./pages/allpapers.php" class="btn btn-primary" style="margin-top:20px !important;">
              <span class="span">Load more</span>

              <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
            </a>
    </div>
</section>



      <!--- #ABOUT -->
      <section class="section about" id="about" aria-label="about">
        <div class="container">
          <div class="about-content">

            <p class="section-subtitle">What is AMU Papers?</p>

            <h2 class="h2 section-title">How does this work</h2>

            <ul class="about-list">

              <li class="about-item">

    

                <div>
                  <h3 class="h3 item-title">About</h3>

                  <p class="item-text">
                    AMU papers is simple and clean UI website that offer a wide variety of PYQ paper PDFs of all departments and courses available at Aligarh Muslim 
                    University. Be it AMU entrance, semester or sessional we got all catergory papers available.
                  </p>
                </div>

              </li>

              <li class="about-item">


                <div>
                  <h3 class="h3 item-title">How to use?</h3>

                  <p class="item-text">
                    A simple registration and login, you are good to go. Dashboard offer user to simply fetch desired paper just by selecting 
                    department and course of paper user wants. sorting options on fetched course papers or simply search.
                  </p>
                </div>

              </li>

              <li class="about-item">


                <div>
                  <h3 class="h3 item-title">For Alumus By Alumus, Help US!</h3>

                  <p class="item-text">
                    Though we are trying to upload every papers we get, but you know it is not possible. so why not help us archieve all AMU papers digitally here for our juniors.
                    When registered with us, you can add a paper pdf and share with all AMU faternity in seconds.
                  </p>
                </div>

              </li>

            </ul>

          </div>

        </div>
      </section>




      <section class="section category" aria-label="category" aria-label="about">
        <div class="container">
        <div class="hero-content">

         <p class="section-subtitle">We have Papers of</p>
        <div id="counter">
        <div class="counter-item" id="departments-counter">0<h3 class="h3 item-title"> </h3></div>
        <div class="counter-item" id="courses-counter">0<h3 class="h3 item-title"> </h3></div>
        <div class="counter-item" id="papers-counter">0<h3 class="h3 item-title"> </h3></div>
        </div>
        </div>
        </div>
      </section>

    </article>
  </main>

    <?php
    require_once "includes/footer.php";

    ?>


<script>
    function openPDF(filePath, paperId) {
        // Open the PDF file in a new tab
        window.open(filePath, '_blank');

        // Send AJAX request to update view count
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "./backend/update_view_count.php", true);
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
/// Function to animate counters from zero to the total count
function animateCounters(totalDepartments, totalCourses, totalPapers) {
  let departmentsCounter = document.getElementById('departments-counter');
  let coursesCounter = document.getElementById('courses-counter');
  let papersCounter = document.getElementById('papers-counter');
  
  let departmentsCount = 0;
  let coursesCount = 0;
  let papersCount = 0;


  let departmentInterval = setInterval(function() {
      departmentsCount++;
      departmentsCounter.textContent = departmentsCount + ' Departments';
      if (departmentsCount >= totalDepartments) {
          clearInterval(departmentInterval);
      }
  }, 500); // Adjust the interval speed as needed

  let courseInterval = setInterval(function() {
      coursesCount++;
      coursesCounter.textContent = coursesCount + ' Courses';
      if (coursesCount >= totalCourses) {
          clearInterval(courseInterval);
      }
  }, 500); // Adjust the interval speed as needed

  let paperInterval = setInterval(function() {
      papersCount++;
      papersCounter.textContent = papersCount + ' Papers';
      if (papersCount >= totalPapers) {
          clearInterval(paperInterval);
      }
  }, 400);   // Adjust the interval speed as needed
}

// Function to check if the user has scrolled near the footer
function checkScrollNearFooter() {
  let footer = document.querySelector('footer');
  let windowHeight = window.innerHeight;
  let scrollPosition = window.scrollY || window.pageYOffset;
  let footerOffset = footer.offsetTop;

  if (scrollPosition + windowHeight >= footerOffset) {
    // Replace these values with actual total counts fetched from the database
    let totalDepartments = <?php echo $total_departments; ?>;
    let totalCourses = <?php echo $total_courses; ?>;
    let totalPapers = <?php echo $total_papers; ?>;

    animateCounters(totalDepartments, totalCourses, totalPapers);
    window.removeEventListener('scroll', checkScrollNearFooter); // Remove the event listener after animation
  }
}

// Add event listener for scroll
window.addEventListener('scroll', checkScrollNearFooter);




    </script>
</body>

</html>