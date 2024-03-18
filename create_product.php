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
        <?php if(isset($_SESSION['privilege']) && $_SESSION['privilege']=='ADMIN'): ?>
            <a class="btn-delete" href="table_products.php">Go Back</a>
            <h1>Create Product</h1>
            <form action="process_product_create.php" method="post">
                <div class="mb-3">
                    <label for="pname" class="form-label">Product Name:</label>
                    <input required maxlength="45" type="text" id="pname" name="pname" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="pprice" class="form-label">Product Price:</label>
                    <input required maxlength="45" type="text" id="pprice" name="pprice" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="pstock" class="form-label">Stock:</label>
                    <input required maxlength="45" type="text" id="pstock" name="pstock" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="pimage" class="form-label">Image URL:</label>
                    <?php
                        $imagesDirectory = 'images/'; // Set the path to your images directory
                        $images = glob($imagesDirectory . '*.jpg'); // Get all .jpg files from the directory

                        echo '<select id="pimage" name="pimage">';

                        foreach ($images as $image) {
                            // Check if the current image matches the product image and should be selected
    
                            echo '<option value="' . htmlspecialchars($image) . '">images/' . htmlspecialchars(basename($image)) . '</option>';
                        }

                        echo '</select>';
                        echo '</div>';
                        echo '<button type="submit" class="btn btn-primary">Submit</button></form>';
                    ?>
                    

        <?php else: ?>
            <div>You are not admin</div>
        <?php endif; ?>
        
    </main>
    <?php
        include "inc/footer.inc.php";
    ?>
</body>
</html>