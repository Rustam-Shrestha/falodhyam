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

// Fetch user details
$user_query = $con->prepare("SELECT * FROM `buyers` WHERE id = ?");
$user_query->execute([$user_id]);
$user_details = $user_query->fetch(PDO::FETCH_ASSOC);

// When we place an order 
if (isset($_POST['place_order'])) {
    $name = $user_details['name'];
    $phone = $user_details['phone'];
    $email = $user_details['email'];
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRIPPED);
    
    if ($_POST['address_option'] == 'existing') {
        $address = $user_details['address'];
        $house_number = $user_details['house_number'];
    } else {
        $address = $_POST['address'];
        $address = filter_var($address, FILTER_SANITIZE_STRIPPED);
        $house_number = filter_var($_POST['house_number'], FILTER_SANITIZE_STRIPPED);
    }

    // Select cart items with parameters
    $verify_cart = $con->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $verify_cart->execute([$user_id]);

    // If we get product_id from URL
    if (isset($_GET['get_id'])) {
        $get_product = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status=? LIMIT 1");
        $get_product->execute([$_GET['get_id'], "active"]);

        if ($get_product->rowCount() > 0) {
            while ($fetch_pro = $get_product->fetch(PDO::FETCH_ASSOC)) {
                // INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `address`, `house_number`, `method`, `product_id`, `price`, `qty`, `status`) VALUES ("sd","666d94ffca9d4", "Rustam", "bcasm2078@gmail.com", "Ktm", "11", "imepay", "bff08623-2b19-11ef-9eab-482ae306821a", 400, 3, CURRENT_TIMESTAMP,"pending");
                $insert_order = $con->prepare("INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `address`, `house_number`, `method`, `product_id`, `price`, `qty`, `date_ordered`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 'pending');");
                $insert_order->execute([uniqid(), $user_id, $name, $phone, $email, $address, $house_number, $method, $fetch_pro['id'], $fetch_pro['price'], $_GET['qty']]); 

                header('location: orders.php');
            }
        } else {
            $warning_msg[] = "Something went wrong";
        }
    } else if ($verify_cart->rowCount() > 0) {
        while ($fci = $verify_cart->fetch(PDO::FETCH_ASSOC)) {
            
            $insert_order = $con->prepare("INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `address`, `house_number`, `method`, `product_id`, `price`, `qty`, `date_ordered`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 'pending');");
            $insert_order->execute([uniqid(), $user_id, $name, $phone, $email, $address, $house_number, $method, $fci['product_id'], $fci['price'], $fci['qty']]);
        }
        
        $delete_cart_id = $con->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart_id->execute([$user_id]);
        
        header('location: orders.php');
    } else {
        $warning_msg[] = "Something went wrong";
    }
}

if (isset($_POST['delete_product'])) {
    $cart_pros = $con->prepare("SELECT COUNT(*) FROM `cart` WHERE user_id = ?");
    $cart_pros->execute([$user_id]);
    $num_cart_pros = $cart_pros->fetchColumn(); 

    if ($num_cart_pros > 1) {
        $proid = $_POST['product_id'];
        $delete_checkout = $con->prepare("DELETE FROM `cart` WHERE product_id = ?");
        $delete_checkout->execute([$proid]);
        $success_msg[] = "Successfully deleted the item from checkout and cart.";
    } else {
        $warning_msg[] = "Cannot buy a null product; must have more than one item in the cart to delete.";
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
    <title>Checkout page</title>
    <style>
        <?php include "././assets/css/style.css"; ?>
        <?php include "././assets/css/signup-style.css"; ?>
        .checkout {
            width: 80%;
            margin: 0 auto;
        }
        .name {
            font-size: 26px !important;
            text-align: center;
        }
        .price, .grand-total {
            text-align: center;
        }
        .box-container .flex div h3, .box-container .flex div p {}

        .checkout input, .checkout select, .checkout p, .checkout h3 {
            width: 80vw;
            height: 40px;
            border-radius: 20px;
            font-size: 18px;
        }

        .flexy {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .summary {
            margin: 19px 0;
            border: 2px solid var(--green);
        }
    </style>
</head>
<body>
    <?php include "./components/_header.php"; ?>
    
    <center><h3>Proceed with the Billing details</h3></center>
    <div class="checkout">
        <div class="title">
            <br><br>
            <?php include "./components/alert.php"; ?>
            <br><br>
            <div class="summary">
                <div class="box-container">
                    <form action="" method="post">
                    <?php
                    $grand_total = 0;
                    if (isset($_GET['get_id'])) {
                        $select_get = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status= ?");
                        $select_get->execute([$_GET['get_id'],"active"]);
                        while ($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)) {
                            $sub_total = $fetch_get['price'];
                            $sub_total *=$_GET["qty"]; 
                            $grand_total += $sub_total;
                            ?>
                            <div class="flex flexy">
                                <img style="width:200px; height:200px;" src="../seller/<?php echo  $fetch_get["image"]?>" alt="Product Image">
                                <div>
                                    <h3 class="name"><?= $fetch_get['name']; ?></h3>
                                    <p class="price"><?= $fetch_get['price']; ?>/-</p>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        $select_cart = $con->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                        $select_cart->execute([$user_id]);
                        if ($select_cart->rowCount() > 0) {
                            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                                $select_products = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status= ?");
                                $select_products->execute([$fetch_cart['product_id'],"active"]);
                                $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                                $sub_total = ($fetch_cart['qty'] * $fetch_product['price']);
                                $grand_total += $sub_total;
                                ?>
                                <div class="flex flexy">
                                    <div>
                                        <img style="width:200px; height:200px;" src="<?php echo $fetch_product['image']?>" alt="Product Image">
                                        <h3 class="name"><?= $fetch_product['name']; ?></h3>
                                        <p class="price"><?= $fetch_cart['qty']; ?> x <?= $fetch_product['price']; ?></p>
                                        <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
                                        <button class="btn" type="submit" name="delete_product">delete</button>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p>No products added yet</p>";
                        }
                    }
                    ?>
                    </form>
                </div>
                <div class="grand-total"><span>Total payable: </span> Rs. <?= $grand_total; ?>/-</div>
            </div>
            <h1>Checkout summary</h1>
            <p>You are paying for all the products. Get your credit cards or wallet ready or go away.</p>
          
        </div>
        <div class="row">
            <form action="" method="post" onsubmit="return validateForm()">
                <h3>Billing details</h3>
                <div class="flex">
                    <div class="box">
                        <div class="input-field">
                            <p>Name: <span>*</span></p>
                            <input type="text" name="name" value="<?= $user_details['name']; ?>" required readonly>
                        </div>
                        <div class="input-field">
                            <p>Number: <span>*</span></p>
                            <input type="number" name="phone" value="<?= $user_details['phone']; ?>" required readonly>
                        </div>
                        <div class="input-field">
                            <p>Email: <span>*</span></p>
                            <input type="email" name="email" value="<?= $user_details['email']; ?>" required readonly>
                        </div>
                        <div class="input-field">
                            <p>Choose address: <span>*</span></p>
                            <select name="address_option" id="address_option" required onchange="toggleAddressFields()">
                                <option value="">Select address option</option>
                                <option value="existing">Existing address</option>
                                <option value="new">New address</option>
                            </select>
                        </div>
                        <div id="existing_address_fields" style="display: none;">
                            <p>Existing Address: <span>*</span></p>
                            <input type="text" name="existing_address" value="<?= $user_details['address']; ?>" readonly>
                            <p>House Number: <span>*</span></p>
                            <input type="text" name="existing_house_number" value="<?= $user_details['house_number']; ?>" readonly>
                        </div>
                        <div id="new_address_fields" style="display: none;">
                            <p>Address: <span>*</span></p>
                            <select name="address" id="address">
                                <option value="">Select your address</option>
                                <?php
                                $places = ["Balaju", "Sukedhara", "Kalanki", "Samakhusi", "Gongabu", "Thamel", "Baneshwor", "Koteshwor", "Maitidevi", "Lalitpur", "Bhaktapur", "Swayambhu", "Chabahil", "Maharajgunj", "Naxal"];
                                foreach ($places as $place) {
                                    echo "<option value=\"$place\">$place</option>";
                                }
                                ?>
                            </select>
                            <p>House number: <span>*</span></p>
                            <input type="text" name="house_number" id="house_number" placeholder="Enter your house number" maxlength="8">
                        </div>
                        <div class="input-field">
                            <p>Payment method: <span>*</span></p>
                            <select name="method">
                                <option value="cash on delivery">cash on delivery</option>
                                <option value="credit card">credit card</option>
                                <option value="UPI">imepay</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="submit" value="place order" class="btn" name="place_order">
            </form>
        </div>
    </div>
    <?php include "./components/_footer.php"; ?>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script>
         <?php include "./js/interact.js"; ?>

        function toggleAddressFields() {
            var addressOption = document.getElementById('address_option').value;
            var existingFields = document.getElementById('existing_address_fields');
            var newFields = document.getElementById('new_address_fields');

            if (addressOption == 'existing') {
                existingFields.style.display = 'block';
                newFields.style.display = 'none';
            } else if (addressOption == 'new') {
                existingFields.style.display = 'none';
                newFields.style.display = 'block';
            } else {
                existingFields.style.display = 'none';
                newFields.style.display = 'none';
            }
        }

        function validateForm() {
            var addressOption = document.getElementById('address_option').value;
            var houseNumber = document.getElementById('house_number').value;
            if (addressOption == 'new' && houseNumber.trim() === "") {
                alert("Please enter your house number");
                return false;
            }
            return true;
        }

        // Ensure the correct fields are shown on page load if there's a value already selected
        window.onload = function() {
            toggleAddressFields();
        };
    </script>
</body>
</html>
