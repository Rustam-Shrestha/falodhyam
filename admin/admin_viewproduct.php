<?php

include 'navbar.php';
include 'component/dbconnect.php';
?>
<?php
if (isset($_POST['delete'])) {

    $product = $_POST['productId'];
    $delete_product = $conn2->prepare("DELETE FROM `products` WHERE `products`.`id` = ?");
    $delete_product->execute([$product]);

}
$view_sellerid = isset($_SESSION['id']) ? $_SESSION['id'] : null;



?>






<?php

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product Page</title>
    <!-- <link rel="stylesheet" href="../style/two.css"> -->
    <link rel="stylesheet" href="style/original1.css">

    <style>
        .farmerpbox {
            /* height:37rem; */
        }

        .farmerseller {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }
    </style>


</head>

<body>

    <div class="carousel">
        <div class="fruitspage">
            <h1 id="heading">ALL PRODUCTS</h1>
        </div>
        <div class="box">

            <a href="dashboard.php">DASHBOARD</a><span>/ ALL PRODUCTS</span>
        </div>
        <!--============================ PRODUCT BOX================================ -->

        <div class="main">

            <section>
                <h1 class="productheading">ALL PRODUCTS</h1>

                <div id="AllProduct">

                    <?php



                    $select_product = $conn2->prepare("SELECT * FROM `products`");
                    $select_product->execute();







                    if ($select_product->rowCount() > 0) {

                        while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {


                            //==================== FOREIGN KEY IMPORT CONCEPT HERE SELLER TABLE IS SELECT ====================================
                            $sellerid = $fetch_product['s-id'];
                            $select_from_foreign = $conn2->prepare("SELECT * FROM `seller` WHERE `s-id` = ?");
                            $select_from_foreign->execute([$sellerid]);
                            $fetch_foreign = $select_from_foreign->fetch(PDO::FETCH_ASSOC);


                            //==================== FOREIGN KEY IMPORT CONCEPT HERE SELLER TABLE IS SELECT ====================================
                    







                            ?>
                            <form action="" method="post">
                                <div class="farmerpbox">
                                    <span class="farmerpstatus" style="<?php if ($fetch_product['status'] == "deactive" || $fetch_product['status'] == "Deactive") {
                                        echo "color:red ";
                                    } ?> "> <?= $fetch_product['status']; ?> </span>

                                    <span class="price">Rs <?= $fetch_product['price'] ?></span>
                                    <div class="farmerseller">
                                        <span class="farmerseller">Seller Id : <?= $fetch_product['s-id'] ?> </span>
                                        <span class="farmerseller">Seller Name : <?= $fetch_foreign['s-name'] ?> </span>
                                    </div>

                                    <input type="hidden" name="productId" value="<?= $fetch_product['id']; ?>">

                                    <div class="farmerpimage">
                                        <img class="Ornamentimage" src="../seller/img/<?= $fetch_product['image']; ?>" alt="">
                                    </div>
                                    <div class="farmerproductname">
                                        <?= $fetch_product['name'] ?>
                                    </div>

                                    <div class="farmerEDRbox">
                                        <a class="btn"
                                            href="admin_editproduct.php?id=<?= $fetch_product['id']; ?>?sid=<?= $fetch_foreign['s-id'] ?> ">Edit</a>
                                        <!-- <button type="submit" name="delete" class="btn" onclick="let a=prompt('Do you really want to delete your products ?');
if(a!=='CONFIRM'){ exit;}
">Delete</button> -->
                                        <button type="submit" name="delete" class="btn"
                                            onclick="confirmDelete()">Delete</button>


                                        <a class="viewpath btn"
                                            href="admin_readproduct.php?post_id=<?= $fetch_product['id']; ?> ?sid=<?= $fetch_foreign['s-id']; ?>">View</a>

                                    </div>



                                </div>








                            </form>


                            <?php
                            //================================================ PHP INDSDE XA HTML ELEMNET ================================================
                        }

                    } else {
                        echo ' <div class="NoProductBox">
    <h1 id="Productheading">NO Product Added Yet By Seller !</h1>
   
    </div>';

                    }

                    //================================================ PHP INDSDE XA HTML ELEMNET ================================================
                    

                    ?>



                </div>
                <!-- </div> -->

            </section>
        </div>

    </div>

    <script>
        function confirmDelete() {
            let a = prompt('Do you really want to delete your products ? IF "YES" then type "CONFIRM" ');
            if (a !== 'CONFIRM') {
                event.preventDefault(); // Prevent form submission
            }
        }
    </script>

</body>

</html>