<!DOCTYPE html>
<html lang="en">
<body>
    <?php
        include "../inc/head.inc.php";
        
        function displayProducts(){
            $success = true;
            $config = parse_ini_file('/var/www/private/db-config.ini');
            if (!$config)
            {
                $errorMsg = "Failed to read database config file.";    
            }
            else{
                $conn = new mysqli(
                            $config['servername'],
                            $config['username'],
                            $config['password'],
                            $config['dbname']
                        );
                // Check connection
                if ($conn->connect_error)
                {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                }
            }

            if ($success != true){
                echo ($errorMsg);
            }
            else{
                
                $stmt = $conn->prepare("SELECT * FROM products");
                $stmt->execute();
                $result = $stmt->get_result();
                echo '<thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Image</th><th>Stock</th><th colspan=2>Actions</th></tr></thead><tbody>';
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row["product_id"] . '</td>';
                        echo '<td>' . $row["product_name"] . '</td>';
                        echo '<td>' . $row["product_price"] . '</td>';
                        echo '<td><img src="' . $row["product_image"] . '" alt="Product Image"></td>';
                        echo '<td>' . $row["product_stock"] . '</td>';
                        echo '<td><a class="btn-update" href="update_product.php?productedit=' . $row["product_id"] . '">Update</a>';
                        echo '<td><a class="btn-delete" href="table_products.php?productdelete=' . $row["product_id"] . '">Delete</a>';
                        echo '</tr>';
                    }
                    $conn->close();
                } else {
                    echo "0 results";
                }
                echo '</tbody>';
        
            }
        }
        function getUpdateFormProduct($id){
            $success = true;
            $config = parse_ini_file('/var/www/private/db-config.ini');
            if (!$config)
            {
                $errorMsg = "Failed to read database config file.";    
            }
            else{
                $conn = new mysqli(
                            $config['servername'],
                            $config['username'],
                            $config['password'],
                            $config['dbname']
                        );
                // Check connection
                if ($conn->connect_error)
                {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                }
            }

            if ($success != true){
                echo ($errorMsg);
            }
            else{
                $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo '<form action="process_product_update.php" method="post">
                    <input hidden type="text" id="pid" name="pid" class="form-control" value="' . $row["product_id"] . '">
                    <div class="mb-3">
                    <label for="pname" class="form-label">Product Name:</label>
                    <input required maxlength="45" type="text" id="pname" name="pname" class="form-control" value="' . $row["product_name"] . '">
                    </div>
                    <div class="mb-3">
                    <label for="pprice" class="form-label">Product Price:</label>
                    <input required maxlength="45" type="text" id="pprice" name="pprice" class="form-control" value="' . $row["product_price"] . '">
                    </div>
                    <div class="mb-3">
                    <label for="pstock" class="form-label">Stock:</label>
                    <input required maxlength="45" type="text" id="pstock" name="pstock" class="form-control" value="' . $row["product_stock"] . '">
                    </div>
                    <div class="mb-3">
                    <label for="pimage" class="form-label">Image URL:</label>';
                    $imagesDirectory = '../images/'; // Set the path to your images directory
                    $images = glob($imagesDirectory . '*.jpg'); // Get all .jpg files from the directory

                    echo '<select id="pimage" name="pimage">';

                    foreach ($images as $image) {
                    // Check if the current image matches the product image and should be selected
                        $selected = ($image == $row["product_image"]) ? 'selected' : '';
    
                        echo '<option value="' . htmlspecialchars($image) . '"' . $selected . '>images/' . htmlspecialchars(basename($image)) . '</option>';
                    }

                    echo '</select>';
                    echo '</div>';
                    // <div class="mb-3">
                    // <label for="privilege" class="form-label">Privilege:</label>
                    // <input id="privilege" name="privilege" class="form-control" value="' . $row["privilege"] . '">
                    // </div>
                    // <div class="mb-3">
                    // echo '<div class="mb-3">';
                    // echo '<label for="privilege" class="form-label">Privilege:</label>';
                    // echo '<select id="privilege" name="privilege" class="form-control">';
                    // echo '<option value="">None</option>'; // For 'null' privilege

                    // // Set 'selected' attribute if the privilege is 'ADMIN'
                    // $selected = ($row["privilege"] === 'ADMIN') ? 'selected' : '';
                    // echo '<option value="ADMIN" ' . $selected . '>ADMIN</option>';

                    // echo '</select>';
                    // echo '</div>';
                    echo '<button type="submit" class="btn btn-primary">Submit</button></form>';
                    
                    $conn->close();
                } else {
                    echo "0 results";
                }
            }
        }
        if (isset ($_GET['productedit']) && isset($_SESSION['privilege']) && $_SESSION['privilege']=='ADMIN'){
            $producteditId = $_GET['productedit'];
            $showUpdateFormProduct = $producteditId;
        }   
        if (isset ($_GET['productdelete']) && isset($_SESSION['privilege']) && $_SESSION['privilege']=='ADMIN'){
            $productdeleteId = $_GET['productdelete'];
            echo '<div id="deleteModal" style="display:block;">
            <p style="color:red; font-weight:bold;">Are you sure you want to delete product with ID ' . htmlspecialchars($productdeleteId) . '?</p>
            <a class="btn-delete" href="table_products.php?productconfirmDelete=' . htmlspecialchars($productdeleteId) . '">Confirm</a>
            <a class="btn-update" href="table_products.php">Cancel</a></div>';

        }
        if (isset ($_GET['productconfirmDelete'])){
            $id = $_GET['productconfirmDelete'];
            $success = true;
            $config = parse_ini_file('/var/www/private/db-config.ini');
            if (!$config)
            {
                $errorMsg = "Failed to read database config file.";    
            }
            else{
                $conn = new mysqli(
                            $config['servername'],
                            $config['username'],
                            $config['password'],
                            $config['dbname']
                        );
                // Check connection
                if ($conn->connect_error)
                {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                }
            }

            if ($success != true){
                echo ($errorMsg);
            }
            else{
                $stmt = $conn->prepare("DELETE FROM products WHERE product_id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                // unset($_GET['delete']);
                $conn->close();
                header('location:table_products.php');
        
            }

        }
        
    ?>


</body>
</html>
