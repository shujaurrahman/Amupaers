<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
session_start();

?>


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
          <a href="http://localhost/amupapers/index.php" class="navbar-link" data-nav-toggler>Home</a>
        </li>

        <?php
        echo ' <li class="navbar-item">
                  <a href="http://localhost/amupapers/pages/about.php" class="navbar-link" data-nav-toggler>About</a>
                </li>';
        ?>

        <?php
        if (isset($_SESSION['username'])) {
          echo '<li class="navbar-item">
        <a href="http://localhost/amupapers/pages/addpaper.php" class="navbar-link" data-nav-toggler>Add Paper</a>
      </li>';
        }

        ?>
        <li class="navbar-item">
          <a href="http://localhost/amupapers/pages/blog.php" class="navbar-link" data-nav-toggler>Blog</a>
        </li>
        <li class="navbar-item">
          <a href="http://localhost/amupapers/pages/contact.php" class="navbar-link" data-nav-toggler>Contact</a>
        </li>
      </ul>
    </nav>

    <div class="header-actions">
      <?php
      //get current URL
      $current_url = $_SERVER['REQUEST_URI'];

      //if admin navigates to homepage our other page of website ,will the dashboard button which redirects to admin dash
      if (isset($_SESSION['role']) && $_SESSION['role'] === 'super admin' || $_SESSION['role'] == 'admin') {
        echo '<a href="http://localhost/amupapers/admin/adminDashboard.php" class="header-action-btn login-btn">Admin Panel</a>';
      }

      //Check if the session is set to username via login
      if (isset($_SESSION['username']) && $_SESSION['code']==0) {

        // If user is logged in
        if (strpos($current_url, '/pages/dashboard.php') == True) {

          // If user is on the dashboard page, display the username
          echo '<a href="http://localhost/amupapers/pages/dashboard.php" class="header-action-btn login-btn">' . $_SESSION['username'] . '</a>';
        } else {

          // If user is not on the dashboard page, display "Dashboard"
          echo '<a href="http://localhost/amupapers/pages/dashboard.php" class="header-action-btn login-btn">Dashboard</a>';

        }

        // Display logout button
        echo '<a href="http://localhost/amupapers/backend/logout.php" class="header-action-btn logout-btn">Logout</a>';

      } else {

        // If user is not logged in, display login/register button
        echo '<a href="http://localhost/amupapers/pages/login.php" class="header-action-btn login-btn">
            <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
            <span class="span">Login</span>
          </a>';
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