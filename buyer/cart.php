<?php
include "./components/connection.php";

session_start();
// starting session for obtaining user login credentials
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = "";
}

// logging out user
if (isset($_POST['logout'])) {
    session_destroy();
    header("location: login.php?logout=1");
    $message[] = "logged out of system";
}

// adding a product in wishlist
// adding to wishlist from cart we may change mind later

// updating cart
if (isset($_POST['update_cart'])) {

    $cart_id = $_POST['cart_id'];
    // filtering the cart id sanitizing and safely
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRIPPED);
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_NUMBER_INT);
    $update_qty = $con->prepare("UPDATE `cart` SET qty= ? WHERE id= ?");
    $update_qty->execute([$qty, $cart_id]);
    $success_msg[] = "cart quantity is updated";

}

// delete from cart
if (isset($_POST['delete_item'])) {
    // Assuming $con is a valid PDO connection
    // obtaining id with hidden input from cart product lists
    $cart_id = $_POST['cart_id'];

    // filtering the cart id sanitizing and safely
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRIPPED);
    // Verify if the cart item exists`
    // cuz if it does not exit mysql returns error`
    $verify_delete_item = $con->prepare('SELECT * FROM `cart` WHERE id= ?');
    $verify_delete_item->execute([$cart_id]);

    // Check if the cart item exists
    if ($verify_delete_item->rowCount() > 0) {
        // Delete the cart item
        $delete_cart_id = $con->prepare('DELETE FROM `cart` WHERE id=?');
        $delete_cart_id->execute([$cart_id]);
        $success_msg[] = "Successfully deleted a cart item";
    } else {
        $error_msg[] = "Error deleting cart item ";
    }
}

// emptying a cart
if (isset($_POST['empty_cart'])) {
    $verify_empty_item = $con->prepare("SELECT * FROM `cart` WHERE user_id= ?");
    $verify_empty_item->execute([$user_id]);
    if ($verify_empty_item->rowCount() > 0) {
        $delete_cart_id = $con->prepare('DELETE FROM `cart` WHERE user_id=?');
        $delete_cart_id->execute([$user_id]);
        $success_msg[] = "emptied a cart item";
    } else {
        $error_msg[] = "Error emptying cart item ";
    }
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
        :root {
            --green: rgba(19, 78, 0, 0.956);
        }

        .carts {
            display: flex;
            justify-content: center;
            align-items: center
        }

        .item {
            padding: 14px;
            margin: 17pX 23px;
            border: 2PX inset var(--green);
            border-radius: 14px;
            ;
        }

        .carts .item .cartimg img {
            max-width: 240px;
            height: auto;
            overflow-y: none;

        }

        .accumulation {
            line-height: 4;
            width: 80%;
            margin: 0 auto;
            text-align: center;
            font-size: 20px;
        }

        .inActive-box {
            display: flex;
        }

        .inActive-element {
            background: #ddd;
            border-color: gray;
        }

        img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            width: 200px;
            border: 1px solid black;
            height: 200px;
            object-fit: contain;
        }

        .box-container,
        .inActive-box {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .item {
            border: 2px outset var(--green);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 8px;
            margin: 14px 17px;
            max-width: 700px;
            min-width: 250px;
            display: flex;
        }
    </style>
    <title>cart page</title>
</head>

<body>
    <?php include "./components/_header.php"; ?>
    <?php require ("./components/alert.php"); ?>
    <section class="sign-board">
        <div class="about-content">

            <h1>items on cart</h1>

        </div>
    </section>




    <section class="carts">


        <div class="box-container">
            <?php
            $grand_total = 0;
            $has_Active_products = false;
            $select_cart = $con->prepare("SELECT * FROM `cart` WHERE user_id= ? ORDER BY date_added DESC");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
                while ($fetch_carts = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                    $select_products = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status= ?");
                    $select_products->execute([$fetch_carts["product_id"], "Active"]);
                    if ($select_products->rowCount() > 0) {
                        $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                        $has_Active_products = true;
                        ?>
                        <form action="" method="POST" class="item">
                            <!-- secretly giving cart id to server -->
                            <input type="hidden" name="cart_id" value="<?= $fetch_carts['id']; ?>">
                            <div class="cartimg">
                                <img src="../seller/img/<?= $fetch_products['image']; ?>" alt="lost img" class="img">
                            </div>
                            <div class="desc">
                                <h1><?= $fetch_products['name']; ?></h1>
                                <p><strong>Price:</strong> Rs. <?= $fetch_products['price'] ?>/- </p>
                                <p><strong>Calculation: </strong> Rs. <?= $fetch_products['price'] ?> &times;
                                    <?= $fetch_carts['qty'] ?> = <?= $fetch_products['price'] * $fetch_carts['qty'] ?>
                                </p>
                                <p class="subtotal"><strong>Sub total:</strong> <span>Rs.
                                        <?= $sub_total = ($fetch_carts['qty'] * $fetch_carts['price']) ?></span> </p>
                            </div>
                            <div class="flex">
                                <input class="btn" type="number" name="qty" required min="1" value=<?= $fetch_carts['qty'] ?>
                                    max="<?= $fetch_products['available_stock']?>" maxlength="2" class="qty">
                                <button type="submit" name="update_cart" class="bx bxs-edit fa-edit btn"></button>
                            </div>
                            <button type="submit" name="delete_item" class="btn"
                                onclick="return confirm('are u sure to delete this item');">delete</button>
                        </form>
                        <?php
                        $grand_total += $sub_total;
                    }
                }
                if (!$has_Active_products) {
                    echo "<p class='empty'>No Active products found in the cart.</p>";
                }
            } else {
                echo "<p class='empty'>No products added yet.</p>";
            }
            ?>
        </div>




    </section>
    <hr>
    <section class="inActive-cart">

        <!-- incative products -->
        <div class="inActive-box">
            <?php
            $has_inActive_products = false;
            $select_cart = $con->prepare("SELECT * FROM `cart` WHERE user_id= ? ORDER BY date_added DESC");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
                while ($fetch_carts = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                    $select_products = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status!= ?");
                    $select_products->execute([$fetch_carts["product_id"], "Active"]);
                    if ($select_products->rowCount() > 0) {
                        $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                        $has_inActive_products = true;
                        ?>
                        <form action="" method="POST" class="item inActive-element">
                            <!-- secretly giving cart id to server -->
                            <input type="hidden" name="cart_id" value="<?= $fetch_carts['id']; ?>">
                            <div class="cartimg">
                                <img src="../seller/img/<?= $fetch_products['image']; ?>" alt="lost img" class="img">
                            </div>
                            <div class="desc">
                                <h1><?= $fetch_products['name']; ?></h1>
                                <p><strong>Price:</strong> Rs. <?= $fetch_products['price'] ?>/- </p>
                                <p><strong>Calculation: </strong> Rs. <?= $fetch_products['price'] ?> &times;
                                    <?= $fetch_carts['qty'] ?> = <?= $fetch_products['price'] * $fetch_carts['qty'] ?>
                                </p>
                                <p class="subtotal"><strong>Sub total:</strong> <span>Rs.
                                        <?= $sub_total = ($fetch_carts['qty'] * $fetch_carts['price']) ?></span> </p>
                            </div>
                            <div class="empty">Product not available</div>
                            <button type="submit" name="delete_item" class="btn"
                                onclick="return confirm('are u sure to delete this item');">delete</button>
                        </form>
                        <?php
                        $grand_total += $sub_total;
                    }
                }
                if (!$has_inActive_products) {
                    echo "<p class='empty'>No inActive products found in the cart.</p>";
                }
            }
            ?>
        </div>
    </section>

    <section class="accumulation">
        <?php
        if ($grand_total > 0) {

            ?>
            <div class="cart-total">
                <div class="final-price">

                    <p>total amount payable : <span>
                            <?= $grand_total; ?>
                        </span></p>
                </div>
                <div class="summary-buttons">

                    <form action="" method="post">
                        <button type="submit" name="empty_cart" class="btn"
                            onclick="return confirm('are you sure to empty your cart');">clear cart</button>

                        <a href="checkout.php" class="btn">proceed to checkout</a>
                    </form>
                </div>
            </div>
            <?php

        }
        ?>


    </section>
    <?php include "./components/_footer.php"; ?>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script>
        <?php include "./js/interact.js"; ?>
    </script>
</body>

</html>