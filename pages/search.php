<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Paper</title>
    <?php require_once "../assets/fonts.php"?>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/search.css">
</head>
<body>
    <?php require_once "../includes/header.php"; ?>
    <main>
    <article>
    <section class="section category" aria-label="category">
            <div class="container">
                <p class="section-subtitle">Amu Papers Search </p>
            </div>
        </section>
        <div class="search-container">
            <input type="text" id="search-input" placeholder="Search AMU Papers...">
            <button id="search-btn">Search</button>
            <!-- Suggestions -->
            <div id="suggestions"></div>
        </div>
                <!-- Tags section -->
        <section class="section tags">
            <div class="container">
                <h3 class="section-title">Search by Tags:</h3>
                <ul class="tag-list" id="all-tags"></ul>
            </div>
        </section>

        <section class="section category" aria-label="category">
            <div class="container">
                <h2 class="h2 section-title" id="section-title"></h2>
                <ul class="grid-list" id="search-results"></ul>
            </div>
        </section>

    </article>
</main>

<script src="../js/search.js" defer></script>
<script>
</script>
<script>
    function openPDF(filePath, paperId) {
        window.open(filePath, '_blank');
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../backend/update_view_count.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log("View count updated successfully.");
                } else {
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
