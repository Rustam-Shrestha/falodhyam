<?php

$db_name="mysql:host=localhost;dbname=falodhyam_parties";
$username="root";
$userpasword="";
$conn= new PDO($db_name,$username,$userpasword);
// $conn object attribute inside objects....




if($conn){
    // echo'database connecetd successfully';

}else{

    echo'database not connected successfully.';

}

?>