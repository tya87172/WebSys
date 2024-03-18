<!DOCTYPE html>
<html lang="en">
    <?php
        include "inc/nav.inc.php";
        include "inc/header.inc.php";
        include "inc/head.inc.php";
        // include "user_query.php";
        include "dashboard_query.php";
        
    ?>
    <head>
    <style>
        .box {
            border: 2px solid #333; /* Border around each box */
            padding: 20px; /* Some padding inside the box */
            margin: 10px; /* Margin around the box */
            text-align: center; /* Center the text */
            transition: all 0.3s ease; /* Smooth transition for hover effect */
            background-color: #ADD8E6; /* Set background color to blue */
            color: black; /* Set text color to black */
            font-weight: bold; /* Make the text bold */
        }

        .box:hover {
            opacity: 0.7; /* Slightly transparent on hover */
            cursor: pointer; /* Change mouse cursor on hover */
        }
        /* Responsive grid layout for the boxes */
        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); /* Responsive columns */
            grid-gap: 10px; /* Gap between columns */
        }
    </style>
    </head>
    <body>
        
        
        <?php if(isset($_SESSION['privilege']) && $_SESSION['privilege']=='ADMIN'): ?>     
            <div class="container">
                <a href="table.php" class="box"><?php getTotalUsers(); ?> Users</a>
                <a href="table_products.php" class="box"><?php getTotalProducts(); ?> Products</a>
                <a href="table_reviews.php" class="box"><?php getTotalReviews(); ?> Reviews</a>
                <a href="table_purchases.php" class="box"><?php getTotalPurchases(); ?> Purchases</a>
            </div>
        <?php else: ?>
            <div>U ARE NOT ADMIN!!!</div>
        <?php endif; ?>
    </body>
    <?php
        include "inc/footer.inc.php";
    ?>
</html>