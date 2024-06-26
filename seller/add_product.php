<?php

session_start();

include 'navbar.php';
include 'component/dbconnect.php';
?>
<?php

$sellerid=$_SESSION['id'];

if(isset($_POST['publish'])){
    $productname = $_POST['name'];
    $productprice = $_POST['price'];
    $productdetail = $_POST['detail'];
    $producttype=$_POST['producttype'];
    $productstock=$_POST['stock'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = "img/".$image;
    $status = 'pending';

    // $status = 'active';

    
    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("INSERT INTO `products` (`name`, `price`, `image`, `product_detail`, `status`,`s-id`,`type`,`available_stock`) VALUES (?, ?, ?, ?, ?,?,?,?)");

    // Bind parameters to the placeholders and execute the statement
    $stmt->bindParam(1, $productname);
    $stmt->bindParam(2, $productprice);
    $stmt->bindParam(3, $image);
    $stmt->bindParam(4, $productdetail);
    $stmt->bindParam(5, $status);
    $stmt->bindParam(6,$sellerid);
    $stmt->bindParam(7,$producttype); 
    $stmt->bindParam(8,$productstock);
    // Execute the prepared statement
       if ($stmt->execute()) {
        // Upload the image file to the specified folder
        move_uploaded_file($image_tmp_name, $image_folder);
        echo "<script>alert('Product inserted successfully.')</script>";
    } else {
        echo "<script>alert('Error: Unable to insert product.')</script>";
    
    }

}

?>

<?php
if(isset($_POST['draft'])){
    $productname = $_POST['name'];
    $productprice = $_POST['price'];
    $productdetail = $_POST['detail'];
    $productstock=$_POST['stock'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = "img/".$image;
    $status = 'deactive';

    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("INSERT INTO `products` (`name`, `price`, `image`, `product_detail`, `status`,`s-id`,`available_stock`) VALUES (?, ?, ?, ?, ?,?,?,?)");

    // Bind parameters to the placeholders and execute the statement
    $stmt->bindParam(1, $productname);
    $stmt->bindParam(2, $productprice);
    $stmt->bindParam(3, $image);
    $stmt->bindParam(4, $productdetail);
    $stmt->bindParam(5, $status);
    $stmt->bindParam(6,$sellerid);
    $stmt->bindParam(7,$productstock);
       // Execute the prepared statement
       if ($stmt->execute()) {
        // Upload the image file to the specified folder
        move_uploaded_file($image_tmp_name, $image_folder);
        echo "<script>alert('Product saved as draft successfully.')</script>";
    } else {
        echo "<script>alert('Error: Unable to saved product as draft.')</script>";
    
    }

 }
 
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product Page</title>
    <!-- <link rel="stylesheet" href="../style/two.css"> -->
    <link rel="stylesheet" href="style/original.css">
</head>

<body>

    <div class="carousel">
        <div class="fruitspage">
            <h1 id="heading">ADD PRODUCTS</h1>
        </div>
        <div class="box">

            <a href="dashboard.php">DASHBOARD</a><span>ADD PRODUCTS</span>
        </div>
        <!--============================ FORM ================================ -->

        <div class="main">

            <section>
                <form action="" method="post" enctype="multipart/form-data">
                    <h1 class="h1Addproduct">ADD PRODUCTS</h1>

                    <div class="input-field">

                        <label for="">Product Name <sup>*</sup></label>
                        <input type="text" name="name" maxlength="20" placeholder="add products name" required>
                    </div>


                    <div class="input-field">
                        <label for="">Product Price Per Kg </label>
                        <!-- <select name="" id="">
<option value="">kg</option>
<option value="">GRAM</option>
<option value=""></option>
 </select> -->
                        <input type="text" name="price" maxlength="26" placeholder="add products price" required>
                    </div>


                    <div class="input-field">
                        <label for="">Available Stock </label>
                        <input type="number" name="stock" maxlength="5" placeholder="add total products available" required>
                    </div>






                    <div class="input-field">
                        <label for="">product type</label>
                      <div>
                        <select name="producttype" id="Type">
                        <option value="Others">Others</option>    
                            <option value="Drupes">Drupes</option>
                            <option value="Pomes">Pomes</option>
                            <option value="Citrus Fruits">Citrus Fruits</option>
                            <option value="Melons">Melons</option>
                            <option value="Dried Fruits">Dried Fruits</option>
                            <option value="Tropical Fruits">Tropical Fruits</option>
                        <option value="Berries">Berries</option>                          
                        
                        </select required>
                        </div>

                    </div>




                    <div class="input-field">
                        <label for="">product detail</label>
                        <textarea name="detail" id="" cols="30" rows="10" placeholder="write product description"
                            required></textarea>
                    </div>

                    <div class="input-field">
                        <label for="">product image <sup>*</sup></label>
                        <input type="file" name="image" accept="image/*" required>
                    </div>

                    <footer class="addproduct-footer">
                        <button type="submit" name="publish" class="btn add-product-btn">publish products</button>
                        <button type="submit" name="draft" class="btn add-product-btn">save as draft</button>
                    </footer>

                </form>

            </section>
        </div>
        <!--============================ FORM ================================ -->

    </div>











</body>

</html>