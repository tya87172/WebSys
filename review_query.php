<!DOCTYPE html>
<html lang="en">
<body>
    <?php
        include "inc/head.inc.php";
        
        function displayReviews(){
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
                
                $stmt = $conn->prepare("SELECT * FROM reviews");
                $stmt->execute();
                $result = $stmt->get_result();
                echo '<thead><tr><th>ID</th><th>Rating</th><th>Review</th><th>User Id</th><th>Product ID</th><th>Action</th></tr></thead><tbody>';
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row["review_id"] . '</td>';
                        echo '<td>' . $row["rating"] . '</td>';
                        echo '<td>' . $row["review"] . '</td>';
                        echo '<td>' . $row["user_id"] . '</td>';
                        echo '<td>' . $row["product_id"] . '</td>';
                        echo '<td><a class="btn-delete" href="table_reviews.php?reviewdelete=' . $row["review_id"] . '">Delete</a>';
                        echo '</tr>';
                    }
                    $conn->close();
                } else {
                    echo "0 results";
                }
                echo '</tbody>';
        
            }
        }
        
          
        if (isset ($_GET['reviewdelete']) && isset($_SESSION['privilege']) && $_SESSION['privilege']=='ADMIN'){
            $reviewdeleteId = $_GET['reviewdelete'];
            echo '<div id="deleteModal" style="display:block;">
            <p style="color:red; font-weight:bold;">Are you sure you want to delete review with ID ' . htmlspecialchars($reviewdeleteId) . '?</p>
            <a class="btn-delete" href="table_reviews.php?reviewconfirmDelete=' . htmlspecialchars($reviewdeleteId) . '">Confirm</a>
            <a class="btn-update" href="table_reviews.php">Cancel</a></div>';

        }
        if (isset ($_GET['reviewconfirmDelete'])){
            $id = $_GET['reviewconfirmDelete'];
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
                $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                // unset($_GET['delete']);
                $conn->close();
                header('location:table_reviews.php');
        
            }

        }
        
    ?>


</body>
</html>
