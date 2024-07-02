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
    <style>
        .modalrus {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-sizing: border-box;
            width: 100vw;
            height: 100vh;
            display: none;

        }


        .modalrus table {
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid white;
        }

        th {
            background-color: rgba(19, 78, 0, 0.6);
        }

        td {
            background-color: rgba(19, 78, 0, 0.3);
        }
    </style>
</head>

<body>

    <div class="carousel">
        <div class="fruitspage">
            <h1 id="heading">ADD PRODUCTS</h1>
            <div id="myModal" class="modalrus">
                <span class="close-btn btn"
                    style="text-align:center;width:40px; height:40px; background:white;border: 2px solid red; color: red"
                    onclick="colse()"> &times;</span>
                <br><br>
                <center>
                    <h1>Fruit categories and where they fall under</h1>
                </center>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Fruits</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Melons</td>
                            <td>watermelon, cantaloupe, honeydew</td>
                        </tr>
                        <tr>
                            <td>Tropical Fruits</td>
                            <td>pineapple, mango, papaya, guava, kiwi</td>
                        </tr>
                        <tr>
                            <td>Berries</td>
                            <td>mulberry, huckleberry, blueberry, raspberry, strawberry, blackberry</td>
                        </tr>
                        <tr>
                            <td>Drupe</td>
                            <td>peach, plum, cherry, apricot, nectarine</td>
                        </tr>
                        <tr>
                            <td>Citrus</td>
                            <td>orange, lemon, lime, grapefruit, tangerine</td>
                        </tr>
                        <tr>
                            <td>Pomes</td>
                            <td>apple, pear</td>
                        </tr>
                        <tr>
                            <td>Dried Fruits</td>
                            <td>raisin, date, apricot (dried)</td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>
        <div class="box">
            <a href="dashboard.php">DASHBOARD</a><span>ADD PRODUCTS</span>
        </div>
        
        <center>Don't know on which category does your fruit lie? click below</center>
        <button class="btn" style="border: 2px solid green" onclick="openit()">View Categories</button>
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




    <script>
        function openit() {
            var modal = document.getElementById('myModal');
            modal.style.display = 'block';
        }

        function colse() {
            var modal = document.getElementById('myModal');
            modal.style.display = 'none';
        }
    </script>
</body>

</html>