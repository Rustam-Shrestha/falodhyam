<?php


session_start();
$sellerid= isset($_SESSION['id'])?$_SESSION['id'] :null;
if(!isset($sellerid)){
      
   header("location:login.php");
exit;
}




include 'navbar.php';
include 'component/dbconnect.php';




$seller=$conn->prepare("SELECT * FROM `seller` WHERE `s-id`=? ");
$seller->execute([$sellerid]);
if($seller->rowCount()>0){

    echo"The seller id is".$sellerid;
    echo "<a href='logout.php'>LOGOUT</a>";
// Logical ERROR
}


// ========================================ORDER PHP CODE ========================================

// ==================SELLER ROWCOUNT PHP CODE ============================================================
$order=$conn->prepare("SELECT * FROM `orders` WHERE `s-id`=? ");
$order->execute([$_SESSION['id']]);

$orderval=$order->rowCount();
$fetchorder = $order->fetch(PDO::FETCH_ASSOC);
// ==================SELLER ROWCOUNT PHP CODE ============================================================



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

.admin-container{
border:solid 2px red;


}

.admin-seller{
    display:flex;
    flex-wrap:wrap;
margin:20px;
    border:solid 2px black;

}
.as-box
{ display:block;
    width:100%;
    border:solid 2px red;
margin:10px;
}
.id,.sname{
    display:block;
font-size:21px;
margin-left:32px;
text-transform:capitalize;
}


.userinformation{
    display:block;
    text-align:center;
font-size:29px;
color:#555;

margin-bottom:23px;
}
#sellerimg{
    border-radius: 135px;
    height: 4rem;
    object-fit:contain;
}
.farmerpimage{
    display:block;
    margin-left:27px;
}
.sellerinform{
    font-size:29px;
    text-align:center;
    display:block;

}
.admins-box{

    margin-top:29px;

box-shadow:var(--box-shadow);
}





</style>



    <link rel="stylesheet" href="style/one.css">
    <link rel="stylesheet" href="style/original.css">
</head>
<body>
    
<div class="carousel">
<div class="fruitspage">
<h1 id="heading">DASHBOARD</h1>
</div>
<div class="box">

<a href="dashboard.php">Home</a><span>Dashboard</span>
</div>


<div class="admins-box">
        <div class="sellerinform"><?=$orderval ?>  </div>

<div class="userinformation">Number of Orders</div>

<div class="farmerEDRbox">
<a class="viewpath btn" href="view_order.php">View</a>
</div>

</div>

</div>








</body>
</html>

