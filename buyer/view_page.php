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
        $select_price->execute([$product_id, "active"]);
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
        $select_price->execute([$product_id, "active"]);
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
            padding:17px;
            box-shadow: 2px 4px 11px rgba(19, 78, 0, 0.956);

        }

        .view_page h1,
        .view_page .price {
            text-align: center;
            margin: 19px 0;
        }

        .view_page form .image-container {

            border: 2px outset var(--green);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 8px;
            margin: 14px 17px;
            max-width: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto
        }


        .image-container img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
        }
        .typeof {
            border: 3px solid rgba(19, 78, 0, 0.956);
            border-right: 0px;
            color: rgba(19, 78, 0, 0.956);
            border-radius: 22px 0 0 22px;
            padding: 8px;
            text-align: right;
            display: flex;
            align-items: flex-end;
            margin-left: auto;
            margin-top: 16px;
            margin-bottom: 16px;
            margin-right: 0;
            box-shadow: 2px 4px 11px rgba(0, 0, 0, 0.250);
            width:200px
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
                $select_product->execute([$pid, "active"]);
                if ($select_product->rowCount() > 0) {
                    while ($fetch_products = $select_product->fetch(PDO::FETCH_ASSOC)) {

                        ?>
                        <form method="post" class="product_form">
                            <div class="image-container">
                                <img class="product-image" src="../seller/<?php echo $fetch_products['image'] ?>"
                                    alt="product picture">
                            </div>
                            <div class="detail">
                                <div class="name">
                                    <h1><?php echo $fetch_products['name'] ?>
                                    </h1>
                                </div>
                                <strong class="typeof"><?= $fetch_products['type'] ?></strong>
                                <div class="price">
                                    Rs.
                                    <?php echo $fetch_products['price'] ?> /-
                                </div>
                                <div class="product-detail">
                                    <strong>Description:</strong> <br />
                                    <p><?php echo $fetch_products['product_detail'] ?></p>
                                </div>
                                <br><hr><br>
                            </div>
                            <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                            <input class="btn quantity" type="number" name="qty" required value="1" min="1" max="99"
                                    maxlength="2" data-product-id="<?= $fetch_products['id']; ?>">
                            <button class="btn" type="submit" name="add_wishlist">add to wishlist<i
                                    class="bx bx-heart"></i></button>
                            <input type="hidden" name="qty" value="1" min="0" class="quantity">
                            <button class="btn" type="submit" name="add_to_cart">add to cart<i class="bx bx-cart"></i></button>
                            <br><br><br>
                            <a href="#" class="btn checkout" data-product-id="<?= $fetch_products['id']; ?>">buy now</a>
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
document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.checkout').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const productId = this.getAttribute('data-product-id');
                    const quantityInput = document.querySelector(`.quantity[data-product-id="${productId}"]`);
                    if (quantityInput) {
                        const quantity = quantityInput.value;
                        window.location.href = `checkout.php?get_id=${productId}&qty=${quantity}`;
                    } else {
                        console.error('Quantity input not found for product ID:', productId);
                    }
                });
            });
        });
        <?php include "./js/interact.js"; ?>
        <?php include "./js/validate.js"; ?>
    </script>
</body>

</html>