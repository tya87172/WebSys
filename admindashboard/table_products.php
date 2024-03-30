<!DOCTYPE html>
<html lang="en">
    <?php
        include "../inc/nav.inc.php";
        include "../inc/header.inc.php";
        include "../inc/head.inc.php";
        // include "user_query.php";
        include "product_query.php";
        
    ?>
    <head>
    </head>
    <body>
        <div style="text-align: right;">
        <a class="btn-update" href="create_product.php">Create New Product</a>
        </div>
        <h1 style="text-align: center;">List of Products</h1>
        <?php if(isset($_SESSION['privilege']) && $_SESSION['privilege']=='ADMIN'): ?>        
            <div class="table-container">
                <table>
                    <?php displayProducts(); ?>
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