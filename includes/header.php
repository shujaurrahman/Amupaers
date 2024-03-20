<header class="header" data-header>
    <div class="container">

      <h1>
        <a href="#" class="logo">AmuPapers</a>
      </h1>

      <nav class="navbar" data-navbar>

        <div class="navbar-top">
          <a href="#" class="logo">AmuPapers</a>

          <button class="nav-close-btn" aria-label="Close menu" data-nav-toggler>
            <ion-icon name="close-outline"></ion-icon>
          </button>
        </div>

        <ul class="navbar-list">

          <li class="navbar-item">
            <a href="http://localhost/amupapers/index.php" class="navbar-link" data-nav-toggler>Home</a>
          </li>

          <li class="navbar-item">
            <a href="#about" class="navbar-link" data-nav-toggler>About</a>
          </li>

          <li class="navbar-item">
            <a href="#" class="navbar-link" data-nav-toggler>Blog</a>
          </li>

          <li class="navbar-item">
            <a href="http://localhost/amupapers/pages/contact.php" class="navbar-link" data-nav-toggler>Contact</a>
          </li>

        </ul>

      </nav>

      <div class="header-actions">
        <?php
        if(isset($_SESSION['username'])) {
          // If user is logged in, display username and logout button
          echo '<a href="http://localhost/amupapers/pages/dashboard.php" class="header-action-btn login-btn">' . $_SESSION['username'] . '</a>';
          echo '<a href="../backend/logout.php" class="header-action-btn logout-btn">Logout</a>';
        } else {
          // If user is not logged in, display login/register button
          echo '<a href="http://localhost/amupapers/pages/register.php" class="header-action-btn login-btn">
                  <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
                  <span class="span">Login / Register</span>
                </a>';
        }
        ?>
        <button class="header-action-btn nav-open-btn" aria-label="Open menu" data-nav-toggler>
          <ion-icon name="menu-outline"></ion-icon>
        </button>

      </div>

      <div class="overlay" data-nav-toggler data-overlay></div>

    </div>
  </header>
