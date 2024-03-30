<!DOCTYPE html>
<html lang="en">

<body>
    <?php
        include "../inc/nav.inc.php";
        include "../inc/header.inc.php";
        include "../inc/head.inc.php";
    ?>
    <main class="container">
        <?php
        /* . Here we can use the $_POST[] superglobal variable 
        to retrieve the values submitted in the
        form.
        */
            
            $pid = $errorMsg = $pname = $pprice = $pimage = "";  // declaring global variables
            $success = true;
            $pstock = 0;

            function saveProductToDB(){
                // saying the variables used in this function are referenced from the global variable outside the scope
                global $pname, $pprice, $pimage, $pstock, $errorMsg, $success, $pid; 
                // Create database connection.
                $config = parse_ini_file('/var/www/private/db-config.ini');
                if (!$config)
                {
                    $errorMsg = "Failed to read database config file.";
                    $success = false;
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
                    else
                    {
                            
                            // Prepare the statement:
                            $stmt = $conn->prepare("UPDATE products SET product_name =?, product_price=?, product_image=?, product_stock=? WHERE product_id=?");
                            // Bind & execute the query statement:
                            $stmt->bind_param("sssii", $pname, $pprice, $pimage, $pstock, $pid);
                        
                            if (!$stmt->execute())
                            {
                                // throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                                $success = false;
                            }
            
                            $stmt->close();
                        
                    }
                    echo "<script>console.log('closing connection...')</script>";
                    $conn->close();
                }
            }

            $pid = $_POST["pid"];

            if (preg_match('/[^0-9]/', $_POST["pstock"])) {
                $errorMsg .= "Stock can only contain numbers.<br>";
                $success = false;
            }
            else if (preg_match('/[^0-9\.\s]/', $_POST["pprice"])) {
                $errorMsg .= "Price can only contain numbers and dot.<br>";
                $success = false;
            }
            else if (preg_match('/[^a-zA-Z0-9\s\.\/\_]/', $_POST["pimage"])) {
                $errorMsg .= "Wrong image path format.<br>";
                $success = false;
            }
            else
            {

                    $pname = $_POST["pname"];
                    $pprice = $_POST["pprice"];
                    $pstock = $_POST["pstock"];
                    $pimage = $_POST["pimage"];
                    
                    
                    // try{
                    saveProductToDB();
                    // }
                    // catch (Exception $e){
                    //     $success = false;
                    //     $errorMsg = $e->getMessage();
                    // }
                    
                
            }
            if ($success)
            {
                echo "<script>console.log('Success.....')</script>";
                echo "<h4>Update successful!</h4>";
                echo "<br>";
                echo "<a href='table_products.php'><button>Back to Product List</button></a>";
            }
            else
            {   
                echo "<script>console.log('Failed.....')</script>";
                echo "<h4>The following input errors were detected:</h4>";
                echo "<p>" . $errorMsg . "</p>";
                echo '<a href="update_product.php?productedit=' . $pid . '"><button>Return to Update</button></a>';
            }
            /*
            * Helper function that checks input for malicious or unwanted content.
            */
            function sanitize_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            
        ?>
    </main>
    <?php
        include "../inc/footer.inc.php";
    ?>
</body>
</html>



