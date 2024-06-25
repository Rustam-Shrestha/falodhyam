<?php
include "./components/connection.php";
session_start();
if (isset ($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = "";
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("location: login.php?logout=1");
    $message[] = "logged out of system";
}
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == "") {
    header('Location: login.php?attempt=1');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        <?php include "././assets/css/style.css"; ?>
        <?php include "././assets/css/orders-style.css"; ?>
    </style>
    <title>products page</title>
</head>

<body>
    <?php include "./components/_header.php"; ?>
    
    <?php require("./components/alert.php"); ?>

    <section class="sign-board">
        <h1>Pending Orders</h1>
        <strong><a style="color:inherit" href="home.php">HOME</a>&nbsp; &nbsp;/ORDERS</strong>
    </section>

    <section class="orderlist">
        <!-- feetch orders from databae -->
        <?php
        // get entries from orders only we need to fetch image and other details after this 
                $select_orders = $con->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY date_ordered DESC");
                $select_orders->execute([$user_id]);
                // if any data is available execute
                if ($select_orders->rowCount() > 0) {
                    // referencing to productid from orders generate product details
                    while ($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                        $select_products = $con->prepare("SELECT * FROM `products` WHERE id=?");
                        $select_products->execute([$fetch_order['product_id']]);
                        if ($select_products->rowCount() > 0) {
                            while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {

                                ?>
                                <div class="order" <?php if ($fetch_order['status'] == 'cancel') {
                                    echo 'style="border:2px solid red; "';
                                }

                                ?>>
                                <!-- giving url parameter as id from order tables fron which we can generate order wrt given id -->
                                    <!-- <a href="view_order.php?get_id=<?= $fetch_order['id']; ?>"> -->
                                        <p class="date"> <i class="bi bi-calender-fill"></i><span>
                                                <?= $fetch_order['date_ordered']; ?>
                                            </span></p>
                                        <!-- source should be #fetch_product['image'] -->
                                        <img src="../seller/<?= $fetch_product['image']; ?>" alt="this is productimage" class="image">
                                        <div class="row">
                                            <!-- the person who ordered this item -->
                                            <h3 class="name">
                                                <?= $fetch_order['name'] ?>
                                            </h3>
                                            <!-- showing summary -->
                                            <p class="price">price: Rs.
                                                <?= $fetch_order['price'] ?> x
                                                <?= $fetch_order['qty'] ?> kg
                                                = <?= $fetch_order['price']*$fetch_order['qty'] ?>
                                            </p>
                                            <p class="status" style="color:<?php if ($fetch_order['status'] == 'delivered') {
                                                echo 'green';
                                            } else if ($fetch_order['status'] == 'canceled') {
                                                echo "red";
                                            } else {
                                                echo "orange";
                                            } ?>">
                                            </p>
                                        </div>
                                    </a>

                                </div>
                                <?php
                            }
                        }
                    }
                } else {
                    echo '<p class="empty">notihing ordered yet</p>';
                }

                ?>
        <!-- <div class="order">
            <h1>Cantapula</h1>
            <img src="././assets/imgs/img3.jpg" alt="ordered item">
            <p><Strong>Receiver name:</Strong> Rustam Shrestha</p>
            <p><Strong>Sub total:</Strong> Rs. 750 x 2 kg = 1500</p>
        </div> -->
    </section>

    <?php include "./components/_footer.php"; ?>
   <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script>
         <?php include "./js/interact.js"; ?>
    </script>
</body>

</html>