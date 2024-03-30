<?php
    if(session_start() == PHP_SESSION_NONE)
    {   
        session_start();
    }
    //edit however you want
?>

<nav class="navbar navbar-expand-lg bg-secondary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img style="width: 60px; height: 60px;" src="/images/techlogo.png" alt="logo"></a>
    
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php">Home</a>
                </li>
                    
                <li class="nav-item">
                    <a class="nav-link" href="/products.php">Products</a>
                </li>
                
                <li>
                    <a class="nav-link" href="/aboutus.php">About Us</a>
                </li>
                <li>
                    <a class="nav-link" href="/cart.php">Cart</a>
                </li>
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && isset($_SESSION['privilege']) && $_SESSION['privilege'] == "ADMIN"): ?> 
                    <li>
                    <a class="nav-link" href="admindashboard/dashboard.php">Admin Panel</a>
                </li>
                    <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?> 
                    <!--for login, edit however you want-->
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                    <form action="/logout.php" method="post" > <!--edit however you want-->
                        <button type="submit" class="nav-link" style="margin: 0 auto;">
                            Logout
                        </button>
                    </form>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/login.php">Login</a>
                </li>
            
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>