

<!-- ======================================SELLER FETCHED============================================ -->

<?php


session_start();
include 'component/dbconnect.php';

$adminid= $_SESSION['adminid'];
$admin=$conn->prepare("SELECT * FROM `admin` WHERE `id`=? ");
$admin->execute([$adminid]);

$fetchadmin = $admin->fetch(PDO::FETCH_ASSOC);

    

?>





<style>

/* ============================ HAMBURGER TYPE TO LOGOUT CSS ============================ */
 
.modal {
  position: fixed;
  top: 2%;
  right: 15%;
  z-index: 9999;
  background: green; /* semi-transparent background */
  padding: 90px;

  display: none;
  border-radius:8px;
  transition: 0.5s;
}

.modal p {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 24px;
  color: tomato;
  cursor: pointer;
  padding: 4px;
  border: 1px solid tomato;
}
/* p:hover{
  color: var(--white);
  background-color: tomato;
  border:1px solid var(--white);

} */

.blob {
  padding: 4px;
  border-radius: 50%;
  background-color: var(--brown);
}
/* ============================ HAMBURGER TYPE TO LOGOUT CSS ============================ */



* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
     --selenagreen: #87a243;
    --green: #7cab05;
    --light-green: #e0ffcd;
    --box-shadow: 0 0 10px rgba(0 0 0/15%);
}

body{
    background:url('../seller/img/body-bg.jpg');
fit:content;
    width:90%;
}
    
header{
position:sticky;
    display:flex;
box-shadow:var(--box-shadow);
align-items:center;
/* border:solid 3px red; */
margin-top:2%;
margin-left:7%;
}
nav{
    display:inline-block;
    flex-wrap:wrap;
/* border:solid 2px green; */
justify-content:center;
width:100%;
text-align:center;
width: 71%;



   
}
nav a{
padding: 0px 20px;
font-size:23px;
text-transform: capitalize;
list-style:none;
cursor:pointer;
color:black;
text-decoration:none;
}

nav a span:hover{
    cursor:pointer;
    color:var(--selenagreen);
}

 #logo{
border-radius:20px;
width:56%;
}
#firstlogo{
    padding: 1%;
width:12%;
}
#menu-btn{
    display:none;
}



/* ==================================media-query==================================== */
     @media(max-width:991px){
#menu-btn{
    display:block
}
        #firstlogo{
    width:23%;
}
.navbar.active{
display:none;
}
.navbar.direction{
    flex-direction:column
}
.navbar{
    /* transition:all 0.7s ease-in; */
/* height: 9rem; */
height: 13rem;
width:100%;
}
#firstlogo{
    margin-left:-72%;
    /* margin-left:7%; */
}

nav a{
    display: block;
/* background:var(--green); */
box-shadow:var(--box-shadow);
font-size:23.3px;
    margin: 9px;
    padding:10px;
}
nav a span{
    color:green;
    transition:2.0s border-bottom;

}
nav a span:hover{
    color:green;
    border-bottom:solid 2px green;
}


header{
    /* justify-content:space-between; */
    flex-direction:column;
}
.icon{
    position: absolute;
    top: 14%;
    right: 7%;
    
}
     }



     @media(max-width:500px){
#logo{
    width:100%;
}
.icon{
    position:absolute;
    top:42%;
}

     }

     /* ==================================media-query==================================== */
     
.icon{
    display:flex;

}
.icon i{
    cursor:pointer;
    font-size:28.5px;
}
#companyname{
    color:black;
    font-weight:bold;
    font-size:20px;
    display:inline-block;
    text-decoration:none;
}
/* .header.scrolled{
    top:0;
    box-shadow: 0 5px 20px 0.1px rgba(0,0,0,0.1);
backdrop-filter:blur(20px);
}
.header.scrolled a:hover{
    color:var(--green);
} */


</style>
<link rel="stylesheet" href="style/one.css">
<link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
<!-- 
<body> -->


<header class="header">
<a id="firstlogo"href=""><img id="logo" src="../seller/img/ourlogo.jpg" alt="no-image" srcset="">
<!-- <span id="companyname">FalfulKarobar</span> -->
<!-- <i class="bx bx-list-plus" id="menu-btn"></i> -->

</a>


<nav class="navbar active">

<a href="admin_dashboard.php"><span>Dashboard</span></a>

<!-- <a href="add_product.php"><span>Add product</span></a> -->

<a href="admin_viewproduct.php"><span>view product</span></a>
<a href=""><span>accounts</span></a>

</nav>

<div class="icon">
<i class="bx bxs-user" id="user-btn"></i>
<i class="bx bx-list-plus" id="menu-btn"></i>

</div>

</header>


<form method="post" id="ham-account">
<div class="modal" id="modal">
    
<p id="terminator">&times;</p>
    <?php
        if (isset($_SESSION['id']) && $_SESSION['id'] != "") {
            echo '<strong style="font-size:18px;color:white">Email:</strong> <span style="font-size:18px;color:black">'.$fetchadmin['useremail'].' </span><br><a href="logout.php">log out from '.$fetchadmin['useremail'];' </button>';
            
        } else {
             
            echo '<a href="login.php" class="btn">login</a>
        <a href="signup.php" class="btn">signup</a>';
        }
        ?>

</div>
</form>

<script>
    var opener = document.getElementById("user-btn");
    var terminator = document.getElementById("terminator");
    var modal = document.getElementById("modal");
    opener.addEventListener("click", () => {
        modal.style.display = "block";
        modal.style.transition = "1.1s";
    })
    terminator.addEventListener("click", () => {
        modal.style.display = "none";
        modal.style.transition = "1.1s";
    })

</script>






<script>
const header=document.querySelector('.header');

function fixedNavbar(){
    header.classList.toggle('scrolled', window.pageYOffset >0);
}
fixedNavbar();
window.addEventListener('scroll',fixedNavbar);





let menu=document.querySelector("#menu-btn");
menu.addEventListener("click",function(){
let nav=document.querySelector('.navbar');
nav.classList.toggle('active');
nav.classList.toggle('direction');


})
// let menu=document.querySelector('#menu-btn');
// menu.addEventListener('click',function(){
// let nav=document.querySelector('.navbar');
// nav.classList.toggle('active');



</script>    




<!-- </body> -->
