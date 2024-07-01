<?php
include_once ('./components/connection.php');
// Fetch order information based on orderid
$orderid = $_GET['orderid'] ?? '';

if (!$orderid) {
    die('Order ID is required');
}

$query = $con->prepare("SELECT * FROM orders WHERE id = :orderid");
$query->execute(['orderid' => $orderid]);
$fetch_order = $query->fetch(PDO::FETCH_ASSOC);

if (!$fetch_order) {
    die('Order not found');
}

// Calculate maximum delivery date
$currentDate = new DateTime();
$maxDeliveryDate = $currentDate->add(new DateInterval('P4D'))->format('Y-m-d');
?>

<!DOCTYPE html>
<html>

<head>
    <title>Order Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .receipt {
            border: 1px solid #ddd;
            padding: 20px;
            width: 600px;
            margin: auto;
        }

        .receipt h1 {
            text-align: center;
        }

        .receipt .details {
            margin-top: 20px;
        }

        .receipt .details div {
            margin-bottom: 10px;
        }

        .print-button {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .print-button button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        td{
            padding:8px;
        }

        <?php
        include "./assets/css/style.css";
        ?>
    </style>
</head>

<body>
    <div class="receipt">
        <center>
            <img src="./assets/imgs/logo.jpg" alt="falodhyam" style="height:40px; width:40px; border-radius:14px">
            <br>
            <b> Falodhyam Pvt Ltd</b>
            <p>Kathmandu</p>
            <p>+977 9861473532</p>
        </center>
        <div class="details">
            <table>
                <thead></thead>
                <tr>
                    <td><strong>Order ID:</strong></td>
                    <td><?php echo $fetch_order['id']; ?></td>
                </tr>
                <tbody>
                    <tr>
                        <td><strong>Customer Name:</strong></td>
                        <td><?php echo $fetch_order['name']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Customer address:</strong></td>
                        <td>
                            <?php
                            
                            echo $fetch_order['address'];
                            ?>  
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Customer house no.:</strong></td>
                        <td>
                            <?php
                            
                            echo $fetch_order['house_number'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Product Name:</strong>
                        </td>
                        <td>
                            <?php
                            $select_pro = $con->prepare("SELECT * FROM `products` WHERE id = ? AND status= ? LIMIT 1");
                            $select_pro->execute([$fetch_order['product_id'], "Active"]);
                            $fetch_pro = $select_pro->fetch(PDO::FETCH_ASSOC);
                            echo $fetch_pro['name'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Quantity:</strong>
                        <td>
                            <?php echo $fetch_order['qty']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Order Date:</strong>
                            <td>
                                <?php echo $fetch_order['date_ordered']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Maximum Delivery Date:</strong>
                                <td>
                                    <?php echo $maxDeliveryDate; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>payment method:</strong></td>
                                <td>
                                    <?php
                                    
                                    echo $fetch_order['method'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <br><br>
                                    <h1>Price:</h1>
                                    <td>
                                        <br><br>
                                        <h2>
                                            
                                            <?php echo "Rs. ". number_format(($fetch_order['price'] * $fetch_order['qty']),2); ?>
                                        </h2>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="print-button">
                        <button class="btn" onclick="window.print()">Print Receipt</buttonclas>
                    </div>
    </div>
</body>

</html>