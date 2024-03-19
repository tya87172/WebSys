<?php
session_start();
if (!isset($_SESSION['cart']))
{
    $_SESSION['cart'] = [];
}

$config = parse_ini_file('/var/www/private/db-config.ini');

if (!$config) 
{
    $errorMsg = "Failed to read database config file.";
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
<?php
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']); // Convert to integer to sanitize input
    $quantity = 1;

    $sql = "SELECT * FROM world_of_pets.products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    } else {
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $product = $result->fetch_assoc();
            
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $found = false;
                foreach ($_SESSION['cart'] as &$item) 
                {
                    if ($item['product_id'] == $productId) 
                    {
                        $item['quantity'] += $quantity;
                        $product["product_stock"] = $product["product_stock"] - $quantity;
                        $found = true;
                        break;
                    }
                }

                if (!$found) 
                {
                    $_SESSION['cart'][] = ['product_id' => $productId, 'quantity' => $quantity];
                }

                echo "<p>Item added to cart.</p>";
                echo "<p>Product ID".$productId."</p>";
                echo "<p>Product Quantity".$quantity."</p>";
            }

            echo "<form method='post'>";
            echo "<img src='" . htmlspecialchars($product["product_image"]) . "' alt='Product Image' />";
            echo "<p>" . htmlspecialchars($product["product_name"]) . "</p>";
            echo "<p>Price: " . htmlspecialchars($product["product_price"]) . "</p>";
            echo "<p>Stock: " . htmlspecialchars($product["product_stock"]) . "</p>";
            echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($product["product_id"]) . "' />";
            echo "<input type='hidden' name='quantity' value='1' />"; // You can change this to allow user input for quantity
            echo "<button type='submit'>Add to Cart</button>";
            echo "</form>";
            
            
        } 
        else 
        {
            echo "Product not found.";
        }
    }
} else {
    echo "No product specified.";
}

?>
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