<!DOCTYPE html>
<html lang="en">
<body>
    <?php
        include "inc/head.inc.php";
        
        function getTotalUsers(){
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
                echo $result->num_rows;
                $conn->close();
                
                
        
            }
        }
        function getTotalReviews(){
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
                echo $result->num_rows;
                $conn->close();
                
                
        
            }
        }
        function getTotalProducts(){
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
                echo $result->num_rows;
                $conn->close();
                
                
        
            }
        }
        
        function getTotalPurchases(){
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
                
                $stmt = $conn->prepare("SELECT * FROM purchase");
                $stmt->execute();
                $result = $stmt->get_result();
                echo $result->num_rows;
                $conn->close();
                
                
        
            }
        }
        
        
    ?>


</body>
</html>
