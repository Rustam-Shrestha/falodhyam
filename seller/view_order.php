<?php

session_start();

include 'component/dbconnect.php';

include 'navbar.php';

// ==================LITTLE BIT CONSFUSION IS IN A PHP AND A HTML MERGE WITH A WHILE LOOOP IN THIS BELOW CODE====================================
// ====================SEE CODEWITHHARRY FOR REFER. IF FOREGETS============================


// $getid=$_GET['post_id'];
// $view_sellerid=$_GET['sid'];

//==================== FOREIGN KEY IMPORT CONCEPT HERE SELLER TABLE IS SELECT ====================================
// $select_from_foreign=$conn->prepare("SELECT * FROM `seller` WHERE `s-id` = ?");
// $select_from_foreign->execute([$view_sellerid]);
// $fetch_foreign=$select_from_foreign->fetch(PDO::FETCH_ASSOC);

// if ($select_from_foreign) {
// $fetch_foreign = $select_from_foreign->fetch(PDO::FETCH_ASSOC);
// Your code to use $fetch_foreign
// } else {
// Handle the case when the query fails
// echo "Error: Unable to fetch seller information.";
// }

//==================== FOREIGN KEY IMPORT CONCEPT HERE SELLER TABLE IS SELECT ====================================



?>
<!--========================================== Delete Operation ========================================== -->

<?php


$Specific_order = $_SESSION['id'];







if (isset($_POST['delete'])) {

  $product = $_POST['productId'];
  $delete_product = $conn->prepare("DELETE FROM `orders` WHERE `orders`.`id` = ?");
  $delete_product->execute([$product]);

}

if (isset($_POST['complete'])) {

  $product = $_POST['productId'];
  $message = "active";
  $complete_product = $conn->prepare("UPDATE `orders` SET `status` = ? WHERE `id` = ?");
  $complete_product->execute([$message, $product]);

}




?>
<!--========================================== Delete Operation ========================================== -->



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style/one.css">
  <link rel="stylesheet" href="style/original.css">
  <style>
    .readprice {
      margin-left: 2rem !important;
    }

    .orderprice {
      display: inline-block;
      font-size: 24px;
      padding: 5px;
      margin-top: 0x;
      margin-bottom: 0px;
      margin-left: 6rem;
      text-transform: capitalize;
      font-weight: bold;
      color: var(--green);



    }

    .farmerOrderbox {
      height: 58rem;
      box-shadow: var(--box-shadow);

    }

    .farmerinform {
      text-align: center;
      font-weight: 400;
      color: black;
      display: block;
      margin-top: 25px;
      font-size: 18px
    }

    /*======================================== MEDAI QUERY ======================================================= */

    @media(max-width:991px) {
      .farmerOrderbox {
        height: 70rem;
        box-shadow: var(--box-shadow);

      }



    }


    /*======================================== MEDAI QUERY ======================================================= */
  </style>
</head>

<body>

  <div class="carousel">
    <div class="fruitspage">
      <h1 id="heading">ORDERS</h1>
    </div>
    <div class="box">

      <a href="dashboard.php">DASHBOARD</a><span>ORDER PRODUCTS</span>
    </div>

    <!--============================ PRODUCT BOX================================ -->

    <div class="main">

      <section>
        <h1 class="productheading">ALL ORDER PRODUCTS</h1>

        <div id="AllProduct">

          <?php


          $select_product = $conn->prepare("SELECT * FROM `orders` WHERE `s-id`=? ");
          $select_product->execute([$Specific_order]);
          if ($select_product->rowCount() > 0) {

            while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {


              // ==================== FETCH PRODUCT TABLE ROW THOROUGH PRODUCT-ID of ORDERS ====================
          
              $product_id = $conn->prepare("SELECT * FROM `products` WHERE `id` =?");
              $product_id->execute([$fetch_product['product_id']]);

              if ($product_id->rowCount() > 0) { ////YEAH IT RUNS
          
                $fetch_idproduct = $product_id->fetch(PDO::FETCH_ASSOC);





              }
              // ================ FETCH PRODUCT TABLE ROW THOROUGH PRODUCT-ID of ORDERS ========================
          







              ?>
              <form action="" method="post">
                <div class="farmerOrderbox">
                  <!-- <span class="seller-id">Product id is <?= $fetch_product['id'] ?> and seller-name is <?= $fetch_foreign['name'] ?> </span> -->

                  <span class="farmerpstatus" style="<?php if ($fetch_product['status'] == "pending") {
                    echo "color:green";
                  } else {
                    echo "color:green";
                  } ?> "> <?= $fetch_product['status']; ?> </span>

                  <!-- ==================================== Total price needs to be inserted ================================== -->



                  <!--========================= FETCH FROM PRODUCTS TABLE THORUGH S-ID OF ORDERS TABLE =================================== -->

                  <div>
                    <span class="orderprice">Price = Rs <?= $fetch_product['price'] ?></span>
                    <span class="orderprice">Quantity = <?= $fetch_product['qty'] ?></span>



                  </div>

                  <div class="farmerproductname">
                    <?= $fetch_idproduct['name'] ?>
                  </div>

                  <div class="farmerpimage" id="farmeridproduct">
                    <img class="Ornamentimage" id="idproducts" src="img/<?= $fetch_idproduct['image']; ?>" alt="">
                  </div>



                  <!--========================= FETCH FROM PRODUCTS TABLE THORUGH S-ID OF ORDERS TABLE =================================== -->

                  <!--========================= This input stores a fetch value on  html tag........============================================= -->

                  <input type="hidden" name="productId" value="<?= $fetch_product['id']; ?>">

                  <span class="orderprice">Total Amount = Rs <?= $fetch_product['price'] * $fetch_product['qty'] ?></span>


                  <div class="farmerinform">
                    <b>Buyer Name : </b><?= $fetch_product['name'] ?>
                  </div>
                  <div class="farmerinform">
                    <b>Buyer Email :</b> <?= $fetch_product['email'] ?>
                  </div>

                  <div class="farmerinform">
                    <b>Buyer Address :</b> <?= $fetch_product['address'] ?>
                  </div>

                  <div class="farmerinform">
                    <b>Buyer House number :</b> <?= $fetch_product['house_number'] ?>
                  </div>
                  <div class="farmerinform">
                    <b>Buyer Phone number :</b> <?= $fetch_product['number'] ?>
                  </div>
                  <div class="farmerinform">
                    <b>Ordered Date :</b> <?= $fetch_product['date_ordered'] ?>
                  </div>








                  <div class="farmerinform">
                    <b>payment method :</b> <?= $fetch_product['method'] ?>
                  </div>



                  <div class="farmerEDRbox">
                    <button type="submit" name="delete" class="btn" onclick="confirmMessage() ">Delete</button>
                    <button type="submit" name="complete" class="btn" onclick="confirmcomplete() ">Completed</button>
                    <a class="viewpath btn" href="dashboard.php "> Go Back</a>

                  </div>



                </div>



                <script>

                  function confirmMessage() {

                    let a = prompt("Do you really want to delete your products?If 'Yes' then TYPE 'CONFIRM'. ");
                    if (a !== 'CONFIRM') {
                      event.preventDefault();



                    }
                  }

                  function confirmcomplete() {
                    let b = prompt("Is your products really submitted to Customers ?If 'Yes' then TYPE 'CONFIRM'. ");
                    if (b !== 'CONFIRM') {
                      event.preventDefault();


                    }

                  }





                </script>






              </form>


              <?php

            }

          } else {
            // <div class="boxxxxxxx"></div>
          }

          ?>



        </div>
        <!-- </div> -->

      </section>
    </div>
  </div>




  </div>








</body>

</html>