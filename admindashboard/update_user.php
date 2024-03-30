<!DOCTYPE html>
<html lang="en">

    
<body>
    <?php
        include "../inc/nav.inc.php";
        include "../inc/header.inc.php";
        include "../inc/head.inc.php";
        include "user_query.php";
    ?>
    <main class="container">
        <?php if(isset($showUpdateForm)): ?>
            <a class="btn-delete" href="table.php">Go Back</a>
            <h1>Update User <?php echo $showUpdateForm; ?></h1>
            <?php getUpdateForm($showUpdateForm); ?>

        <?php else: ?>
            <div>Error</div>
        <?php endif; ?>
        
    </main>
    <?php
        include "../inc/footer.inc.php";
    ?>
</body>
</html>