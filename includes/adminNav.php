
<header class="header" data-header>
    <div class="container">

      <h1>
        <a href="http://localhost/amupapers/index.php" class="logo">AmuPapers</a>
      </h1>

      <nav class="navbar" data-navbar>

        <div class="navbar-top">
          <a href="http://localhost/amupapers/index.php" class="logo">AmuPapers</a>

          <button class="nav-close-btn" aria-label="Close menu" data-nav-toggler>
            <ion-icon name="close-outline"></ion-icon>
          </button>
        </div>

        <ul class="navbar-list">

          <li class="navbar-item">
            <a href="http://localhost/amupapers/admin/adminDashboard.php" class="navbar-link" data-nav-toggler>Home</a>
          </li>

          <li class="navbar-item">
            <a href="http://localhost/amupapers/admin/actionpaper.php" class="navbar-link" data-nav-toggler>Papers</a>
          </li>

          <li class="navbar-item">
            <a href="http://localhost/amupapers/admin/testimonials.php" class="navbar-link" data-nav-toggler>Querries</a>
          </li>
          
        </ul>

      </nav>

      <div class="header-actions">
        <?php
      session_start();
      $current_url = $_SERVER['REQUEST_URI'];
      if (isset ($_SESSION['username'])) {
        //redirection to user panel
        echo '<a href="http://localhost/amupapers/pages/dashboard.php" class="header-action-btn login-btn">User Dashboard</a>';
        // If user is logged in
        if (strpos($current_url, '/admin/adminDashboard.php') !== false) {
          // If user is on the dashboard page, display the username
          echo '<a href="http://localhost/amupapers/admin/adminDashboard.php" class="header-action-btn login-btn">' . $_SESSION['username'] . '</a>';
        } else {
          // If user is not on the dashboard page, display "Dashboard"
          echo '<a href="http://localhost/amupapers/admin/adminDashboard.php" class="header-action-btn login-btn">Dashboard</a>';
        }
        // Display logout button
        echo '<a href="http://localhost/amupapers/backend/logout.php" class="header-action-btn logout-btn">Logout</a>';
      } 
      ?>
        <button class="header-action-btn nav-open-btn" aria-label="Open menu" data-nav-toggler>
          <ion-icon name="menu-outline"></ion-icon>
        </button>

      </div>

      <div class="overlay" data-nav-toggler data-overlay></div>

    </div>
    <script src="http://localhost/amupapers/js/script.js" defer></script>
  </header>