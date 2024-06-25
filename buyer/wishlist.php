<?php
include "./components/connection.php";
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = "";
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("location: login.php?logout=1");
    $message[] = "logged out of system";
}
// adding a product in wishlist
if (isset($_POST['add_wishlist'])) {
    $id = uniq_id();
    $product_id = $_POST['product_id'];
    $verify_wishlist = $con->prepare('SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ? ORDER BY date_added DESC');
    $verify_wishlist->execute([$user_id, $product_id]);
    $cart_num = $con->prepare('SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?');
    $cart_num->execute([$user_id, $product_id]);
    if ($verify_wishlist->rowCount() > 0) {
        $warning_msg[] = 'product already exists in your wishlist';

    } else if ($cart_num->rowCount() > 0) {
        $warning_msg[] = 'product already exists in your wishlist';

    } else {
        $select_price = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status= ? LIMIT 1");
        $select_price->execute([$product_id,"active"]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);
        $insert_wishlist = $con->prepare("INSERT INTO `wishlist` (id, user_id, product_id, price) VALUES(?,?,?,?)");
        $insert_wishlist->execute([$id, $user_id, $product_id, $fetch_price['price']]);
        $success_msg[] = 'successfully added to wiahlist';
    }
}

// adding a product in cart
if (isset($_POST['add_to_cart'])) {
    $id = uniq_id();
    $product_id = $_POST['product_id'];
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRIPPED);

    $verify_cart = $con->prepare('SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?');
    $verify_cart->execute([$user_id, $product_id]);

    $max_cart_items = $con->prepare("SELECT * FROM `cart` WHERE user_id=? ");
    $max_cart_items->execute([$user_id]);
    if ($verify_cart->rowCount() > 0) {
        $warning_msg[] = 'product already in your cart';

    } else if ($max_cart_items->rowCount() > 20) {
        $warning_msg[] = 'cart is already full';

    } else {
        $select_price = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status= ? LIMIT 1");
        $select_price->execute([$product_id, "active"]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);
        $insert_cart = $con->prepare("INSERT INTO `cart` (id, user_id, product_id, price, qty) VALUES(?,?,?,?,?)");
        $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);
        $success_msg[] = 'successfully added to cart';
    }
}
// delete from wishlist
if (isset($_POST['delete_item'])) {
    $wishlist_id = $_POST['wishlist_id'];
    $wishlist_id = filter_var($wishlist_id, FILTER_SANITIZE_STRIPPED);
    $verify_delete_item = $con->prepare("SELECT * FROM `wishlist` WHERE id=? ORDER BY date_added DESC");
    $verify_delete_item->execute([$wishlist_id]);
    if ($verify_delete_item->rowCount() > 0) {
        $delete_wishlist_id = $con->prepare("DELETE FROM `wishlist` WHERE id=?");
        $delete_wishlist_id->execute([$wishlist_id]);
        $success_msg[] = "successfully deleted an wishlist item";

    } else {
        $warning_msg[] = "wishlist item already deleted";
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

        .wishlists {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            flex-wrap: wrap;
        }
        .box-container{
            display:flex;   
            flex-wrap:wrap;
            flex-direction:flex-start;
            justify-content:center;
        }

        .item {
            padding: 14px;
            margin: 8px 12px;
            border: 2px inset var(--green);
            border-radius: 14px;
            max-width: 260px;
            text-align: center;
            height: 6%00px;  
            /* display: flex; */
        }

        .item .wishlistimg img {
            max-width: 240px;
            max-height:240px;   
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

        .wishlist-buttons {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin-top: 10px;
        }

        .wishlist-buttons button,
        .wishlist-buttons a {
            margin: 5px;
            padding: 5px 10px;
        }

        .wishlist-buttons .btn {
            background-color: var(--green);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .wishlist-buttons .btn:hover {
            background-color: #135c00;
        }

        .name {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .flex {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .price {
            font-size: 16px;
        }

        .empty {
            text-align: center;
            font-size: 18px;
        }
    </style>
    <title>wishlists page</title>
</head>

<body>
    <?php include "./components/_header.php"; ?>

    <section class="sign-board">
        <div class="about-content">
            <h1>Items on Wishlists</h1>
        </div>
    </section>

    <center><h1 class="title">Products Added in Wishlist</h1></center>
    <?php include "./components/alert.php"; ?>
    <section class="wishlists">
        <div class="box-container">
            <?php
            $grand_total = 0;
            $select_wishlist = $con->prepare("SELECT * FROM `wishlist` WHERE user_id= ? ORDER BY date_added DESC");
            $select_wishlist->execute([$user_id]);
            if ($select_wishlist->rowCount() > 0) {
                while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
                    $select_products = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status= ?");
                    $select_products->execute([$fetch_wishlist["product_id"],"active"]);
                    if ($select_products->rowCount() > 0) {
                        $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
            ?>
                        <div class="item">
                            <form action="" method="post" class="box">
                                <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id'] ?>">
                                <div class="wishlistimg">
                                    <img src="../seller/<?= $fetch_products['image'] ?>" alt="Product Image" class="img">
                                </div>
                                <div class="wishlist-buttons">
                                    <button type="submit" name="add_to_cart" class="btn"><i class="bx bx-cart"></i></button>
                                    <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="bx bxs-show btn"></a>
                                    <button type="submit" name="delete_item" class="btn" onclick="return confirm('Delete this item?')"><i class="bx bx-x"></i></button>
                                </div>
                                <h3 class="name"><?= $fetch_products['name']; ?></h3>
                                <input type="hidden" name="product_id" value="<?= $fetch_products['id'] ?>">
                                <div class="flex">
                                    <p class="price">Price: Rs. <?= $fetch_products['price'] ?>/-</p>
                                    <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn" style="font-size:13px;">Buy Now</a>
                                </div>
                            </form>
                        </div>
            <?php
                        $grand_total += $fetch_products['price'];
                    }
                }
            } else {
                echo "<p class='empty'>No products added yet.</p>";
            }
            ?>
        </div>
        
        <!-- inactive wishlist -->
        <div class="box-container" style="background:#ddd; border-color:gray:">
            <?php
            $grand_total = 0;
            $select_wishlist = $con->prepare("SELECT * FROM `wishlist` WHERE user_id= ? order by date_added DESC");
            $select_wishlist->execute([$user_id]);
            if ($select_wishlist->rowCount() > 0) {
                while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
                    $select_products = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status != ?");
                    $select_products->execute([$fetch_wishlist["product_id"],"active"]);
                    if ($select_products->rowCount() > 0) {
                        $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
            ?>
                        <div class="item">
                            <form action="" method="post" class="box">
                                <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id'] ?>">
                                <div class="wishlistimg">
                                    <img src="../seller/<?= $fetch_products['image'] ?>" alt="Product Image" class="img">
                                </div>
                                <div class="wishlist-buttons">
                                   <div class="empty">item is not available</div>
                                </div>
                                <h3 class="name"><?= $fetch_products['name']; ?></h3>
                                <input type="hidden" name="product_id" value="<?= $fetch_products['id'] ?>">
                                <div class="flex">
                                    <p class="price">Price: Rs. <?= $fetch_products['price'] ?>/-</p>
                                    <button type="submit" name="delete_item" class="btn" onclick="return confirm('Delete this item?')"><i class="bx bx-x"></i></button>
                                </div>
                            </form>
                        </div>
            <?php
                        $grand_total += $fetch_products['price'];
                    }
                }
            } else {
                echo "<p class='empty'>No products added yet.</p>";
            }
            ?>
        </div>
    </section>

    <section class="accumulation">
        <strong>Total Amount Payable: </strong><span>Rs. <?= $grand_total ?></span>
        <!-- <div class="buttons">
            <a href="clear_cart.php" class="btn">Clear Cart</a>
            <a href="checkout_cart.php" class="btn">Proceed to Checkout from Cart</a>
        </div> -->
    </section>

     <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
     <script>
         <?php include "./js/interact.js"; ?>
     </script>
    <?php include "./components/_footer.php"; ?>
</body>


</html>