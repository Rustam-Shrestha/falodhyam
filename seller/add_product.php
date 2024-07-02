<?php
session_start();

include 'navbar.php';
include 'component/dbconnect.php';

// Ensure the session ID is set
if (!isset($_SESSION['id'])) {
    echo "<script>alert('You need to log in first.')</script>";
    exit;
}

$sellerid = $_SESSION['id'];

if (isset($_POST['publish']) || isset($_POST['draft'])) {
    $productname = $_POST['name'];
    $productprice = $_POST['price'];
    $productdetail = $_POST['detail'];
    $producttype = $_POST['producttype'];
    $productstock = $_POST['stock'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = "img/" . $image;
    $status = isset($_POST['publish']) ? 'pending' : 'deactive';

    if ($productprice < 10) {
        echo '<script>alert("Product price cannot be less than 10")</script>';
    } elseif ($productstock < 10) {
        echo '<script>alert("Product stock cannot be less than 10 ")</script>';
    } else {
        // $check_product = $conn->prepare("SELECT * FROM `products` WHERE `name` = ? AND `image` = ?");
        // $check_product->execute([$productname, $image]);

        $check_productextra = $conn->prepare("SELECT * FROM `products` WHERE `image` = ? AND `s-id` = ? ");
        $check_productextra->execute([$image, $_SESSION['id']]);
        if ($check_productextra->rowCount() > 0) {
            echo '<script>alert("Duplicate Image of Product.")</script>';
            exit;
        }



        $check_product = $conn->prepare("SELECT * FROM `products` WHERE `name` = ? AND `s-id` = ? ");
        $check_product->execute([$productname, $_SESSION['id']]);

        if ($check_product->rowCount() > 0) {
            echo '<script>alert("Duplicate Product Name.")</script>';

        } else {
            $stmt = $conn->prepare("INSERT INTO `products` (`name`, `price`, `image`, `product_detail`, `status`, `s-id`, `type`, `available_stock`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $productname);
            $stmt->bindParam(2, $productprice);
            $stmt->bindParam(3, $image);
            $stmt->bindParam(4, $productdetail);
            $stmt->bindParam(5, $status);
            $stmt->bindParam(6, $sellerid);
            $stmt->bindParam(7, $producttype);
            $stmt->bindParam(8, $productstock);

            if ($stmt->execute()) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message = isset($_POST['publish']) ? 'Product inserted successfully.' : 'Product saved as draft successfully.';
                echo "<script>alert('$message')</script>";
            } else {
                echo "<script>alert('Error: Unable to insert product.')</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product Page</title>
    <link rel="stylesheet" href="style/original.css">
    <script>
        function validateInput(input) {
            if (input.value < 100) {
                input.value = 100;
            }
        }
    </script>
</head>

<body>

    <div class="carousel">
        <div class="fruitspage">
            <h1 id="heading">ADD PRODUCTS</h1>
        </div>
        <div class="box">
            <a href="dashboard.php">DASHBOARD</a><span>ADD PRODUCTS</span>
        </div>

        <div class="main">
            <section>
                <form action="" method="post" enctype="multipart/form-data">
                    <h1 class="h1Addproduct">ADD PRODUCTS</h1>

                    <div class="input-field">
                        <label for="">Product Name <sup>*</sup></label>
                        <input type="text" name="name" maxlength="20" placeholder="Add product name" required>
                    </div>

                    <div class="input-field">
                        <label for="">Product Price Per Kg</label>
                        <input type="number" name="price" placeholder="Add product price" required>
                    </div>

                    <div class="input-field">
                        <label for="">Available Stock</label>
                        <input type="number" name="stock" step="1" placeholder="Add total product available"
                            oninput="validateInput(this)" required>
                    </div>

                    <div class="input-field">
                        <label for="">Product Type</label>
                        <div>
                            <select name="producttype" id="Type" required>
                                <option value="Others">Others</option>
                                <option value="Drupes">Drupes</option>
                                <option value="Pomes">Pomes</option>
                                <option value="Citrus Fruits">Citrus Fruits</option>
                                <option value="Melons">Melons</option>
                                <option value="Dried Fruits">Dried Fruits</option>
                                <option value="Tropical Fruits">Tropical Fruits</option>
                                <option value="Berries">Berries</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-field">
                        <label for="">Product Detail</label>
                        <textarea name="detail" cols="30" rows="10" placeholder="Write product description"
                            required></textarea>
                    </div>

                    <div class="input-field">
                        <label for="">Product Image <sup>*</sup></label>
                        <input type="file" name="image" accept="image/*" required>
                    </div>

                    <footer class="addproduct-footer">
                        <button type="submit" name="publish" class="btn add-product-btn">Publish Product</button>
                        <button type="submit" name="draft" class="btn add-product-btn">Save as Draft</button>
                    </footer>
                </form>
            </section>
        </div>
    </div>

</body>

</html>