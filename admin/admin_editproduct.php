<?php
include 'navbar.php';
include 'component/dbconnect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get product ID safely
$getpid = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($getpid <= 0) {
    echo "<script>alert('Invalid product ID'); window.location.href = 'admin_viewproduct.php';</script>";
    exit();
}

// Fetch product details
$editproduct = $conn2->prepare("SELECT * FROM `products` WHERE `id`=?");
$editproduct->execute([$getpid]);
$fetch_product = $editproduct->fetch(PDO::FETCH_ASSOC);

if (!$fetch_product) {
    echo "<script>alert('Product not found!'); window.location.href = 'admin_viewproduct.php';</script>";
    exit();
}

// Update Product Status
if (isset($_POST['update'])) {
    if (!empty($_POST['status'])) {
        $status = $_POST['status'];
        $updateproduct = $conn2->prepare("UPDATE `products` SET `status`=? WHERE `id`=?");

        if ($updateproduct->execute([$status, $getpid])) {
            echo "<script>alert('Product Updated Successfully'); window.location.href = 'admin_viewproduct.php';</script>";
        } else {
            $error = $updateproduct->errorInfo();
            echo "<script>alert('Error updating product: " . addslashes($error[2]) . "');</script>";
        }
    } else {
        echo "<script>alert('Please select a status');</script>";
    }
}

// Delete Product
if (isset($_POST['delete'])) {
    $delete_product = $conn2->prepare("DELETE FROM `products` WHERE `id` = ?");
    
    if ($delete_product->execute([$getpid])) {
        echo "<script>alert('Product Deleted Successfully'); window.location.href = 'admin_viewproduct.php';</script>";
    } else {
        $error = $delete_product->errorInfo();
        echo "<script>alert('Error deleting product: " . addslashes($error[2]) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product Page</title>
    <link rel="stylesheet" href="style/original1.css">
    <style>
        #ProductStatusUpdate {
            width: 100%;
            font-size: 20px;
            padding: 8px;
            margin-bottom: 12px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 5px 10px 0.1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <div class="carousel">
        <div class="fruitspage">
            <h1 id="heading">EDIT PRODUCTS</h1>
        </div>
        <div class="box">
            <a href="dashboard.php">DASHBOARD</a><span>/ EDIT PRODUCTS</span>
        </div>

        <div class="main">
            <section>
                <form action="" method="post">
                    <h1 class="h1Addproduct">EDIT PRODUCTS</h1>
                    <input type="hidden" name="productId" value="<?= htmlspecialchars($fetch_product['id']); ?>">

                    <div class="input-field">
                        <label for="">Product Status <sup>*</sup></label>
                        <select name="status" id="ProductStatusUpdate">
                            <option value="active" <?= ($fetch_product['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                            <option value="deactive" <?= ($fetch_product['status'] == 'deactive') ? 'selected' : '' ?>>Deactive</option>
                        </select>
                    </div>

                    <div class="input-field">
                        <label for="">Product Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($fetch_product['name']) ?>" disabled>
                    </div>

                    <div class="input-field">
                        <label for="">Product Price</label>
                        <input type="text" name="price" value="<?= htmlspecialchars($fetch_product['price']) ?>" disabled>
                    </div>

                    <div class="input-field">
                        <label for="">Product Detail</label>
                        <textarea name="detail" cols="30" rows="10" disabled><?= htmlspecialchars($fetch_product['product_detail']) ?></textarea>
                    </div>

                    <div class="input-field">
                        <label for="">Product Image <sup>*</sup></label>
                        <input type="file" name="image" accept="image/*" disabled>
                    </div>
                    <div class="input-field">
                        <img class="Ornamentimage" src="seller/img/<?= htmlspecialchars($fetch_product['image']) ?>" alt="">
                    </div>

                    <div class="farmerEDRbox">
                        <button type="submit" name="update" class="btn">Update</button>
                        <a class="viewpath btn" href="admin_viewproduct.php">Go Back</a>
                        <button type="submit" name="delete" class="btn" onclick="return confirmMessage()">Delete</button>
                    </div>
                </form>
            </section>
        </div>

        <script>
            function confirmMessage() {
                let confirmation = prompt("Do you really want to delete this product? Type 'CONFIRM' to proceed.");
                return confirmation === 'CONFIRM';
            }
        </script>
    </div>
</body>

</html>

