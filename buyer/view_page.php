<?php include "./components/connection.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $verify_wishlist = $con->prepare('SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ?');
    $verify_wishlist->execute([$user_id, $product_id]);

    if ($verify_wishlist->rowCount() > 0) {
        $warning_msg[] = 'product already exists in your wishlist';

    } else {
        $select_price = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status= ? LIMIT 1");
        $select_price->execute([$product_id,"active"]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);
        $insert_wishlist = $con->prepare("INSERT INTO `wishlist` (id, user_id, product_id, price) VALUES(?,?,?,?)");
        $insert_wishlist->execute([$id, $user_id, $product_id, $fetch_price['price']]);
        $success_msg[] = 'successfully added to wishlist';
    }
}

// adding a product in cart
if (isset($_POST['add_to_cart'])) {
    $id = uniq_id();
    $product_id = $_POST['product_id'];
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_NUMBER_INT);

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
        $select_price->execute([$product_id,"active"]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);
        $insert_cart = $con->prepare("INSERT INTO `cart` (id, user_id, product_id, price, qty) VALUES(?,?,?,?,?)");
        $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);
        $success_msg[] = 'successfully added to cart';
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>product detail</title>
    <style>
        <?php include "././assets/css/style.css"; ?>
        <?php include "././assets/css/products-style.css"; ?>


        .products {
            width: 80%;
            margin: 0 auto;
        }

        .view_page {
            margin: 0 auto;

        }

        .view_page h1,
        .view_page .price {
            text-align: center;
            margin: 19px 0;
        }
    </style>
</head>

<body>
    <?php include "./components/_header.php"; ?>
    <div class="products">

        <?php include "./components/alert.php"; ?>
        <section class="view_page">
            <?php
            if (isset($_GET['pid'])) {
                $pid = $_GET['pid'];
                $select_product = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status= ?");
                $select_product->execute([$pid,"active"]);
                if ($select_product->rowCount() > 0) {
                    while ($fetch_products = $select_product->fetch(PDO::FETCH_ASSOC)) {

                        ?>
                        <form method="post" class="product_form">
                            <img class="product-image" src="<?php echo $fetch_products['image'] ?>" alt="product picture">
                            <div class="detail">
                                <div class="name">
                                    <h1><?php echo $fetch_products['name'] ?>
                                    </h1>
                                </div>
                                <div class="price">
                                    Rs.
                                    <?php echo $fetch_products['price'] ?> /-
                                </div>
                                <div class="product-detail">
                                    <p><?php echo $fetch_products['product_detail'] ?></p>
                                </div>
                            </div>
                            <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                            <button class="btn" type="submit" name="add_wishlist">add to wishlist<i
                                    class="bx bx-heart"></i></button>
                            <input type="hidden" name="qty" value="1" min="0" class="quantity">
                            <button class="btn" type="submit" name="add_to_cart">add to cart<i class="bx bx-cart"></i></button>
                            <br><br><br>
                            <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn">buy now</a>
                        </form>
                        <?php
                    }
                }
            }

            ?>
        </section>
    </div>
    <?php include "./components/_footer.php"; ?>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    
    <script>

        <?php include "./js/interact.js"; ?>
        <?php include "./js/validate.js"; ?>
    </script>
</body>

</html>