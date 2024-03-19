<?php
$config = parse_ini_file('/var/www/private/db-config.ini');
$success = true; //assume success unless an error occurs

if (!$config) 
{
    $errorMsg = "Failed to read database config file.";
    $success = false;
} 
else 
{
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );

    //check connection
    if ($conn->connect_error) 
    {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } 
    else 
    {
        //query for product table
        $sql = "SELECT product_id, product_name, product_price, product_image, product_stock FROM world_of_pets.products";
        $result = $conn->query($sql);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>WSTech APlus</title>
    <?php
        include "inc/head.inc.php";
    ?>
    
</head>
    
<body>
    <header class="jumbotron text-center text-light bg-dark rounded-0">
    
    <?php
        include "inc/nav.inc.php";
    ?>
        
    </header>
    
    <main class="container">
        <div>
            
        </div>
        
        <div>
            <table>
                <?php
                 if ($success && $result->num_rows > 0) 
                 {
                    $counter = 0; // Initialize counter
                    // output data of each row
                    while ($row = $result->fetch_assoc()) 
                    {
                        // Check if it's the start of a new row
                        if ($counter % 3 == 0) 
                        {
                            //close previous row after 3 items and start new row
                            if ($counter != 0) 
                            {
                                echo "</tr>";
                            }
                            echo "<tr>";
                        }
                            echo "<td>";
                            echo "<a href='product_detail.php?id=" . $row["product_id"] . "'>";
                            echo "<img src='" . $row["product_image"] . "' alt='Product Image' /><br>";
                            echo "</a><br>";
                            echo "" . $row["product_name"] . "<br>";
                            echo "". $row["product_price"] . "<br>";
                            echo "</td>";
                            $counter++; //increase counter for number of items in one row
                    }
                        // Check if you need to close the last row
                    if ($counter % 3 != 0) 
                    {
                        echo "</tr>";
                    }
                } 
                elseif ($success) 
                {
                    echo "<tr><td colspan='3'>No results found</td></tr>";
                } 
                else 
                {
                    echo "<tr><td colspan='3'>" . $errorMsg . "</td></tr>";
                }
                ?>
            </table>
        </div>
    </main>

</body>

<?php
include "inc/footer.inc.php";
?>

</html>
<?php
//close connection
if ($conn) 
{
    $conn->close();
}
?>