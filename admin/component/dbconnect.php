<?php


$db_name2="mysql:host=localhost;dbname=falodhyam_parties";
$username2="root";
$userpasword2="";
$conn2= new PDO($db_name2,$username2,$userpasword2);
// $conn object attribute inside objects....




if($conn2){
    echo'database two connecetd successfully';

}else{

    echo'database not connected successfully.';

}



$db_name="mysql:host=localhost;dbname=falodhyam_admin";
$username="root";
$userpasword="";
$conn= new PDO($db_name,$username,$userpasword);
// $conn object attribute inside objects....




if($conn){
    echo'database connecetd successfully';

}else{

    echo'database not connected successfully.';

}


?>