<!DOCTYPE html>
<html lang="en">

    
<body>
    <?php
        include "inc/nav.inc.php";
        include "inc/header.inc.php";
        include "inc/head.inc.php";
        include "product_query.php";
    ?>
    <main class="container">
        <?php if(isset($showUpdateFormProduct)): ?>
            <a class="btn-delete" href="table_products.php">Go Back</a>
            <h1>Update Product <?php echo $showUpdateFormProduct; ?></h1>
            <?php getUpdateFormProduct($showUpdateFormProduct); ?>

        <?php else: ?>
            <div>Error form not set</div>
        <?php endif; ?>
        
    </main>
    <?php
        include "inc/footer.inc.php";
    ?>
</body>
</html>