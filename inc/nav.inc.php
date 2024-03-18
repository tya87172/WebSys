<?php session_start(); ?>

<nav class="navbar navbar-expand-sm bg-secondary" data-bs-theme="dark">
        <a class="navbar-brand" href="#"><img src="images/jerry.jpg" width="30" height="30" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Dogs</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#cat">Cats</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#cat">Products</a>
              </li>
              <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true): ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
              <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </nav>