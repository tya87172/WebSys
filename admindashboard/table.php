<!DOCTYPE html>
<html lang="en">
    <?php
        include "../inc/nav.inc.php";
        include "../inc/header.inc.php";
        include "../inc/head.inc.php";
        include "user_query.php";
        
    ?>
    <head>
    </head>
    <body>
        <h1 style="text-align: center;">List of Users</h1>
        <?php if(isset($_SESSION['privilege']) && $_SESSION['privilege']=='ADMIN'): ?>        
            <div class="table-container">
                <table>
                    <?php displayUsers(); ?>
                </table>
            </div>
        <?php else: ?>
            <div>U ARE NOT ADMIN!!!</div>
        <?php endif; ?>
    </body>
    <?php
        include "../inc/footer.inc.php";
    ?>
</html>
