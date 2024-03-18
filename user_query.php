<!DOCTYPE html>
<html lang="en">
<body>
    <?php
        include "inc/head.inc.php";
        
        function displayUsers(){
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
                
                $stmt = $conn->prepare("SELECT * FROM users");
                $stmt->execute();
                $result = $stmt->get_result();
                echo '<thead><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Privilege</th><th colspan=2>Actions</th></tr></thead><tbody>';
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row["user_id"] . '</td>';
                        echo '<td>' . $row["fname"] . '</td>';
                        echo '<td>' . $row["lname"] . '</td>';
                        echo '<td>' . $row["email"] . '</td>';
                        echo '<td>' . $row["privilege"] . '</td>';
                        echo '<td><a class="btn-update" href="update_user.php?edit=' . $row["user_id"] . '">Update</a>';
                        echo '<td><a class="btn-delete" href="table.php?delete=' . $row["user_id"] . '">Delete</a>';
                        echo '</tr>';
                    }
                    $conn->close();
                } else {
                    echo "0 results";
                }
                echo '</tbody>';
        
            }
        }
        function getUpdateForm($id){
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
                $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo '<form action="process_user_update.php" method="post">
                    <input hidden type="text" id="userid" name="userid" class="form-control" value="' . $row["user_id"] . '">
                    <div class="mb-3">
                    <label for="fname" class="form-label">First Name:</label>
                    <input maxlength="45" type="text" id="fname" name="fname" class="form-control" value="' . $row["fname"] . '">
                    </div>
                    <div class="mb-3">
                    <label for="lname" class="form-label">Last Name:</label>
                    <input required maxlength="45" type="text" id="lname" name="lname" class="form-control" value="' . $row["lname"] . '">
                    </div>
                    <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input required maxlength="45" type="email" id="email" name="email" class="form-control" value="' . $row["email"] . '">
                    </div>';
                    // <div class="mb-3">
                    // <label for="privilege" class="form-label">Privilege:</label>
                    // <input id="privilege" name="privilege" class="form-control" value="' . $row["privilege"] . '">
                    // </div>
                    // <div class="mb-3">
                    echo '<div class="mb-3">';
                    echo '<label for="privilege" class="form-label">Privilege:</label>';
                    echo '<select id="privilege" name="privilege" class="form-control">';
                    echo '<option value="">None</option>'; // For 'null' privilege

                    // Set 'selected' attribute if the privilege is 'ADMIN'
                    $selected = ($row["privilege"] === 'ADMIN') ? 'selected' : '';
                    echo '<option value="ADMIN" ' . $selected . '>ADMIN</option>';

                    echo '</select>';
                    echo '</div>';
                    echo '<button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    </form>';
                    
                    $conn->close();
                } else {
                    echo "0 results";
                }
            }
        }
        if (isset ($_GET['edit']) && isset($_SESSION['privilege']) && $_SESSION['privilege']=='ADMIN'){
            $editId = $_GET['edit'];
            $showUpdateForm = $editId;
        }   
        
        if (isset ($_GET['delete']) && isset($_SESSION['privilege']) && $_SESSION['privilege']=='ADMIN'){
            $deleteId = $_GET['delete'];
            echo '<div id="deleteModal" style="display:block;">
            <p style="color:red; font-weight:bold;">Are you sure you want to delete user with ID ' . htmlspecialchars($deleteId) . '?</p>
            <a class="btn-delete" href="table.php?confirmDelete=' . htmlspecialchars($deleteId) . '">Confirm</a>
            <a class="btn-update" href="table.php">Cancel</a></div>';

        }
        if (isset ($_GET['confirmDelete'])){
            $id = $_GET['confirmDelete'];
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
                $stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                // unset($_GET['delete']);
                $conn->close();
                header('location:table.php');
        
            }

        }
    ?>


</body>
</html>
