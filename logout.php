<!DOCTYPE html>
<html lang="en">

    
<body>
    <?php
        include "inc/nav.inc.php";
        include "inc/header.inc.php";
        include "inc/head.inc.php";
    ?>
    <main class="container">
        <h1>Logged Out Successfully</h1>
        
        <?php
            $_SESSION = array();
            session_destroy();
        ?>
        <div class="mb-3">
            <a href='login.php'><button>Return to Login</button></a>
            
        </div>
    </main>
    <?php
        include "inc/footer.inc.php";
    ?>
</body>
</html>