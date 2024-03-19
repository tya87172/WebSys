<!DOCTYPE html>
<html lang="en">
<head>
        <?php
			include "inc/head.inc.php";
		?>
        
</head>
<body>
    <?php
    include "inc/nav.inc.php";
    ?>
    <?php
// Check if the cart items session variable is already set
    if (!isset($_SESSION['cartitems'])) {
        $_SESSION['cartitems'] = array();
    }
    $item_id = 1;
    $quantity = 3;
    $_SESSION['cartitems'][$item_id] = $quantity;
?>
    <main>
        <h1>Shopping Cart</h1>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <td colspan="2">Product</td>
                            <td>Price</td>
                            <td>Quantity</td>
                            <td>Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($_SESSION['cartitems'])): ?>
                        <tr>
                            <td colspan="5" class="text">You have no products added in your Shopping Cart</td>
                        </tr>
                        <?php else : ?>
                        <?php foreach ($_SESSION['cartitems'] as $item_id => $quantity): ?>
                        <tr>
                            <?php
                                echo "<td><img src='images/cat_food.jpg' alt='cat food' style='width: 100px'></td>";
                                echo "<td>$item_id</td>";
                                echo "<td>99</td>";
                                echo "<td>$quantity</td>";
                                echo "<td>99</td>";
                            
                            ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="subtotal">
    <div class="subtotal-text">
        <span class="text">Subtotal</span>
        <span class="price">&dollar;<?=$subtotal?></span>
    </div>
    <div class="subtotal-button">
        <button type="submit" class="btn btn-primary btnorder" name="placeorder">Place Order</button>
    </div>
</div>

        </main>
    <?php
        include "inc/footer.inc.php";
    ?>
</body>
</html>