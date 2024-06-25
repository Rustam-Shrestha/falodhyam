<?php
include "./components/connection.php";

// session checkpoint
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

// product page needs to have ability to store data from products table to carts and wishlists

// adding a product in wishlist
if (isset($_POST['add_to_wishlist'])) {
    $id = uniqid();
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
    $id = uniqid();
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
// Get selected type from URL parameters
$type = isset($_GET['type']) ? $_GET['type'] : 'all';
$type_filter = "";

if ($type !== 'all') {
    $type_filter = "AND type = ?";
}

$query = "SELECT * FROM `products` WHERE status=? $type_filter";
$select_products = $con->prepare($query);

if ($type !== 'all') {
    $select_products->execute(["active", $type,]);
} else {
    $select_products->execute(["active"]);
}

// Function to get active class for the category
function getActiveClass($current_type, $type)
{
    return $current_type === $type ? 'active' : '';
}
// if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == "") {
//     header('Location: login.php?attempt=1');
//     exit();
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        <?php include "././assets/css/style.css"; ?>
        <?php include "././assets/css/products-style.css"; ?>
        .category-box {
            cursor: pointer;
            padding: 10px;
            margin: 5px;
            border: 1px solid rgba(19, 78, 0, 0.956);
            display: inline-block;
        }

        .category-box.active {
            background-color: rgba(19, 78, 0, 0.956);
            color: white;
        }

        .item {
            margin: 20px;

        }

        .box {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            display: inline-block;
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
            box-shadow: 2px 4px 11px rgba(0, 0, 0, 0.250)
        }
    </style>
    <title>products page</title>
</head>

<body>
    <?php include "./components/_header.php"; ?>

    <section class="sign-board">
        <h1>All products</h1>
        <strong><a style="color:inherit" href="home.php">HOME</a>&nbsp; &nbsp;/PRODUCTS</strong>
    </section>
    <?php require ("./components/alert.php"); ?>

    <!-- filter container -->
    <div class="container">

        <div class="categories">
            <div class="category-box <?= getActiveClass('all', $type) ?>" onclick="window.location.href='?type=all';">
                All</div>
            <div class="category-box <?= getActiveClass('berries', $type) ?>"
                onclick="window.location.href='?type=berries';">Berries</div>
            <div class="category-box <?= getActiveClass('drupes', $type) ?>"
                onclick="window.location.href='?type=drupes';">Drupes</div>
            <div class="category-box <?= getActiveClass('pomes', $type) ?>"
                onclick="window.location.href='?type=pomes';">Pomes</div>
            <div class="category-box <?= getActiveClass('citrus fruits', $type) ?>"
                onclick="window.location.href='?type=citrus fruits';">Citrus Fruits</div>
            <div class="category-box <?= getActiveClass('melons', $type) ?>"
                onclick="window.location.href='?type=melons';">Melons</div>
            <div class="category-box <?= getActiveClass('dried fruits', $type) ?>"
                onclick="window.location.href='?type=dried fruits';">Dried Fruits</div>
            <div class="category-box <?= getActiveClass('tropical fruits', $type) ?>"
                onclick="window.location.href='?type=tropical fruits';">Tropical Fruits</div>
            <div class="category-box <?= getActiveClass('others', $type) ?>"
                onclick="window.location.href='?type=others';">Others</div>
        </div>

    </div>
    <section class="products">
        <div class="item">
            <?php
            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <form action="" method="post" class="box">
                        <img src="../seller/<?= $fetch_products['image']; ?>" class='img' />
                        <?php
                        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == "") {
                            echo "<div style='background-color:rgba(19, 78, 0, 0.956); color:white'>login for more features </div>";
                        } else {
                            echo '<div class="buttons">
                    <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                    <button type="submit" name="add_to_wishlist" value="' . $fetch_products["id"] . '"><i class="bx bx-heart"></i></button>

                    <a href="view_page.php?pid=' . $fetch_products["id"] . '" class="bx bxs-show"></a>
                    </div>';
                        }
                        ?>
                        <h3 class="name"> <?php
                        $product_name = $fetch_products['name'];
                        if (strlen($product_name) > 20) {
                            $product_name = htmlspecialchars(substr($product_name, 0, 20)) . '... ' . '<a style="color:#888 !important;" href="view_page.php?pid=' . $fetch_products["id"] . '">More</a>';
                        }
                        echo $product_name;
                        ?> </h3>
                        <strong class="typeof"><?= $fetch_products['type'] ?></strong>
                        <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
                        <div class="flex">
                            <p class="price">price: Rs. <?= $fetch_products['price']; ?>/-
                                <input class="btn quantity" type="number" name="qty" required value="1" min="1" max="99"
                                    maxlength="2" data-product-id="<?= $fetch_products['id']; ?>">
                            </p>
                        </div>
                        <br><br>
                        <a href="#" class="btn checkout" data-product-id="<?= $fetch_products['id']; ?>">buy now</a>
                    </form>
                    <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>

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