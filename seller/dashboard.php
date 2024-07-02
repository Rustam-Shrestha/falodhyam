<?php
session_start();
$sellerid = isset($_SESSION['id']) ? $_SESSION['id'] : "";

if (!$sellerid) {
    header("location:login.php");
    exit;
}

include 'navbar.php';
include 'component/dbconnect.php';

$seller = $conn->prepare("SELECT * FROM `seller` WHERE `s-id` = ?");
$seller->execute([$sellerid]);
$fetchname = $seller->fetch(PDO::FETCH_ASSOC);


if ($seller->rowCount() > 0) {

}

// ========================================ORDER PHP CODE ========================================

$order = $conn->prepare("SELECT * FROM `orders` WHERE `s-id` = ?");
$order->execute([$sellerid]);

$orderval = $order->rowCount();
$fetchorder = $order->fetch(PDO::FETCH_ASSOC);

// ========================================ORDER PHP CODE ========================================

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* ORDER CSS IS IMPORTED FROM ADMIN_DASHBOARD.php CSS */
        .admin-container {
            border: solid 2px red;
        }

        .admin-seller {
            display: flex;
            flex-wrap: wrap;
            margin: 20px;
            border: solid 2px black;
        }

        .as-box {
            display: block;
            width: 100%;
            border: solid 2px red;
            margin: 10px;
        }

        .id,
        .sname {
            display: block;
            font-size: 21px;
            margin-left: 32px;
            text-transform: capitalize;
        }

        .userinformation {
            display: block;
            text-align: center;
            font-size: 29px;
            color: #555;
            margin-bottom: 23px;
        }

        #sellerimg {
            border-radius: 135px;
            height: 4rem;
            object-fit: contain;
        }

        .farmerpimage {
            display: block;
            margin-left: 27px;
        }

        .sellerinform {
            font-size: 29px;
            text-align: center;
            display: block;
        }

        .admins-box {
            margin-top: 29px;
            box-shadow: var(--box-shadow);
        }

        ol {
            width: 80%;
            margin: 0 auto;
        }

        ol li {
            font-size: 20px;
            color: white;
            text-align: justify;
        }

        #fruitshead {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.7);
            flex-direction: column;
            height: 22rem;
            margin-top: 10px;
        }


        .label-container {

            width: 86%;
            margin: 0 auto;
            padding: 12px 0;
            display: flex;
            justify-content: space-between;

        }
    </style>

    <link rel="stylesheet" href="style/one.css">
    <link rel="stylesheet" href="style/original.css">
</head>

<body>

    <div class="wallpaper">
        <?php
        if (isset($_SESSION['id']) == true) {
            $info_msg[] = "Welcome, " . $fetchname['s-name'] . "!";
        }

        ?>
        <?php require ("component/alert.php"); ?>

    </div>
    <script>
        function closeLabel() {
            var label = document.querySelector('.label-container');
            label.style.display = 'none';
        }
    </script>



    <div class="carousel">
        <div id="fruitshead">
            <h1 id="heading">Roles and Regolations </h1>
            <ol>
                <li>Product descriptions, images, and prices must be accurate and truthfol. Misleading information is
                    prohibited.</li>
                <li>Prices shoold be fair and transparent, including all taxes and additional costs clearly stated.
                </li>
                <li>Sellers must comply with food safety standards and regolations, ensuring fruits are free from
                    harmfol chemicals or pesticides.</li>
                <li>All fruits sold must be fresh and of high quality. Spoiled or substandard products are not
                    allowed.</li>
                <li>Sellers cannot add a duplicate name or image of products.</li>
                <li>Seller account will be deleted if they add more than 3 fake products.</li>
            </ol>
        </div>
        <div class="box">
            <a href="dashboard.php">Home</a><span>Dashboard</span>
        </div>
        <div class="admins-box">
            <div class="sellerinform"><?= $orderval ?> </div>
            <div class="userinformation">Number of Orders</div>
            <div class="farmerEDRbox">
                <a class="viewpath btn" href="view_order.php">View</a>
            </div>
        </div>
    </div>

</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Find the element with id 'fruitshead'
        const fruitshead = document.getElementById('fruitshead');

        if (fruitshead && fruitshead.parentNode.tagName === 'A' && fruitshead.parentNode.style.display === 'none') {
            const parent = fruitshead.parentNode;
            // Insert the 'fruitshead' element before the parent 'a' tag
            parent.parentNode.insertBefore(fruitshead, parent);
            // Remove the parent 'a' tag
            parent.parentNode.removeChild(parent);
        }
    });
</script>



</html>