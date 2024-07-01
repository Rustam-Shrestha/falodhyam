<?php
include "./components/connection.php";

session_start();
// Starting session for obtaining user login credentials
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = "";
}

// Logging out user
if (isset($_POST['logout'])) {
    session_destroy();
    header("location: login.php?logout=1");
    exit();
}

// Updating cart
if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    // Filtering the cart id sanitizing and safely
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_NUMBER_INT);
    $update_qty = $con->prepare("UPDATE `cart` SET qty= ? WHERE id= ?");
    $update_qty->execute([$qty, $cart_id]);
    $success_msg[] = "Cart quantity is updated";
}

// Delete from cart
if (isset($_POST['delete_item'])) {
    $cart_id = $_POST['cart_id'];
    // Filtering the cart id sanitizing and safely
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);
    // Verify if the cart item exists
    $verify_delete_item = $con->prepare('SELECT * FROM `cart` WHERE id= ?');
    $verify_delete_item->execute([$cart_id]);

    // Check if the cart item exists
    if ($verify_delete_item->rowCount() > 0) {
        // Delete the cart item
        $delete_cart_id = $con->prepare('DELETE FROM `cart` WHERE id=?');
        $delete_cart_id->execute([$cart_id]);
        $success_msg[] = "Successfully deleted a cart item";
    } else {
        $error_msg[] = "Error deleting cart item";
    }
}

// Emptying a cart
if (isset($_POST['empty_cart'])) {
    $verify_empty_item = $con->prepare("SELECT * FROM `cart` WHERE user_id= ?");
    $verify_empty_item->execute([$user_id]);
    if ($verify_empty_item->rowCount() > 0) {
        $delete_cart_id = $con->prepare('DELETE FROM `cart` WHERE user_id=?');
        $delete_cart_id->execute([$user_id]);
        $success_msg[] = "Emptied the cart";
    } else {
        $error_msg[] = "Error emptying cart";
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
        .carts .item .cartimg img {
            max-width: 240px;
            height: auto;
            overflow-y: none;
            margin: 11px
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
            width: 80%
        }
        .item {
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            max-width: 300px !important;
            min-width: 250px !important;
            display: flex;
            padding: 14px;
            margin: 17pX 23px;
            border: 3px solid var(--green);
            border-radius: 14px;
        }
        .flex button,
        .flex input {
            margin: 13px;
        }
    </style>
    <title>Cart Page</title>
</head>

<body>
    <?php include "./components/_header.php"; ?>
    <?php require("./components/alert.php"); ?>
    <section class="sign-board">
        <div class="about-content">
            <h1>Items in Cart</h1>
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
                        $fetch_sname = $con->prepare("SELECT * FROM seller WHERE `s-id`= ?");
                        $fetch_sname->execute([$fetch_products['s-id']]);
                        $sname = $fetch_sname->fetch(PDO::FETCH_ASSOC);
                        $has_Active_products = true;
                        ?>
                        <form action="" method="POST" class="item" style="height:540px">
                            <!-- secretly giving cart id to server -->
                            <input type="hidden" name="cart_id" value="<?= htmlspecialchars($fetch_carts['id']); ?>">
                            <div class="cartimg">
                                <img src="../seller/img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="lost img" class="img">
                            </div>
                            <div class="desc">
                                <h1>
                                    <?php
                                    $product_name = $fetch_products['name'];
                                    if (strlen($product_name) > 20) {
                                        $product_name = htmlspecialchars(substr($product_name, 0, 20)) . '... ' . '<a style="color:#888 !important;" href="view_page.php?pid=' . htmlspecialchars($fetch_products["id"]) . '">More</a>';
                                    } else {
                                        $product_name = htmlspecialchars($product_name);
                                    }
                                    echo $product_name;
                                    ?>
                                </h1>
                                <strong>From: </strong><?= htmlspecialchars($sname['s-name']); ?>
                                <p><b>Available stock: </b><?= htmlspecialchars($fetch_products["available_stock"]); ?></p>
                                <p><strong>Price:</strong> Rs. <?= htmlspecialchars($fetch_products['price']); ?>/- </p>
                                <p><strong>Calculation: </strong> Rs. <?= htmlspecialchars($fetch_products['price']); ?> &times; <?= htmlspecialchars($fetch_carts['qty']); ?></p>
                                <p class="subtotal"><strong>Sub total:</strong> <span>Rs. <?= htmlspecialchars($fetch_carts['qty'] * $fetch_carts['price']); ?></span></p>
                            </div>
                            <?php if ($fetch_products['available_stock'] > 0) { ?>
                                <div class="flex">
                                    <?php
                                    if ($fetch_products['available_stock'] < $fetch_carts['qty']) {
                                        $newqty = $fetch_products['available_stock'];
                                        $updateCart = $con->prepare("UPDATE cart SET qty = ? WHERE id = ?");
                                        $updateCart->execute([$newqty, $fetch_carts['id']]);
                                    }
                                    ?>
                                    <input class="btn" type="number" name="qty" required min="1" value="<?= htmlspecialchars($fetch_carts['qty']); ?>" max="<?= htmlspecialchars($fetch_products['available_stock']); ?>" maxlength="2" class="qty">
                                    <button type="submit" name="update_cart" class="bx bxs-edit fa-edit btn"></button>
                                    <a class="btn" href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>"><i class="bx bx-show"></i></a>
                                </div>
                            <?php } else { ?>
                                <div class="empty">Product is not available</div>
                            <?php } ?>
                            <button type="submit" name="delete_item" class="btn" onclick="return confirm('Are you sure to delete this item?');">Delete</button>
                        </form>
                        <?php
                        $grand_total += $fetch_carts['qty'] * $fetch_carts['price'];
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
        <!-- Inactive products -->
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
                            <input type="hidden" name="cart_id" value="<?= htmlspecialchars($fetch_carts['id']); ?>">
                            <div class="cartimg">
                                <img src="../seller/img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="lost img" class="img">
                            </div>
                            <div class="desc">
                                <h1><?= htmlspecialchars($fetch_products['name']); ?></h1>
                                <p><strong>Price:</strong> Rs. <?= htmlspecialchars($fetch_products['price']); ?>/- </p>
                                <p><strong>Calculation: </strong> Rs. <?= htmlspecialchars($fetch_products['price']); ?> &times; <?= htmlspecialchars($fetch_carts['qty']); ?></p>
                                <p class="subtotal"><strong>Sub total:</strong> <span>Rs. <?= htmlspecialchars($fetch_carts['qty'] * $fetch_carts['price']); ?></span></p>
                            </div>
                            <div class="empty">Product not available</div>
                            <button type="submit" name="delete_item" class="btn" onclick="return confirm('Are you sure to delete this item?');">Delete</button>
                        </form>
                        <?php
                        $grand_total += $fetch_carts['qty'] * $fetch_carts['price'];
                    }
                }
                if (!$has_inActive_products) {
                    echo "<p class='empty'>No inactive products found in the cart.</p>";
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
                    <p>Total amount payable : <span>Rs. <?= htmlspecialchars($grand_total); ?></span></p>
                </div>
                <div class="summary-buttons">
                    <form action="" method="post">
                        <button type="submit" name="empty_cart" class="btn" onclick="return confirm('Are you sure to empty your cart?');">Clear Cart</button>
                        <a href="checkout.php" class="btn">Proceed to Checkout</a>
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

