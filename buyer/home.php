<!DOCTYPE html>
<html lang="en">
<?php
include "./components/connection.php";
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = "";
}
if (isset($_POST['logout'])) {
    session_destroy();
    header("location: login.php?logout=1");
    $message[] = "logged out of system";
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        <?php include "./assets/css/style.css"; ?>
    </style>
    <title>Home page</title>
</head>

<body>
    <?php
    include "./components/_header.php";
    ?>
    <div class="wallpaper">
        <?php
        if (isset($_GET['loggedin']) && $_GET['loggedin'] == true) {
            $info_msg[] = "Welcome, " . $_SESSION['user_name'] . "!";
        }

        ?>
        <?php require ("./components/alert.php"); ?>
        <div class="carousel-container">
            <button class="carousel-btn-prev carousel-btn" onclick="prevSlide()">
                &#8249;
            </button>
            <div class="carousel">
                <img src="./assets/imgs/img1.jpg" alt="image 1">
                <img src="./assets/imgs/img2.jpg" alt="image 2">
                <img src="./assets/imgs/img3.jpg" alt="image 3">
                <img src="./assets/imgs/img4.jpg" alt="image 4">
                <img src="./assets/imgs/img1.jpg" alt="image 1">
                <img src="./assets/imgs/img2.jpg" alt="image 2">
                <img src="./assets/imgs/img3.jpg" alt="image 3">
                <img src="./assets/imgs/img4.jpg" alt="image 4">
            </div>
            <button class="carousel-btn carousel-btn-next" onclick="nextSlide()">
                &#8250;
            </button>
        </div>
    </div>
    <!-- all top 4 items are shown in this section -->

    

    <!-- sponsored banner and discount items are shown here -->
    
    <h1 style=" background: linear-gradient(45deg, red, yellow, red);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  color: transparent; text-align:center;">Trending Bulks</h1>
    <section class="trendings">

        <a href="http://localhost:8000/wip/falodhyam/buyer/view_products.php?type=berries">
            <div class="trending-item">
                <img src="../seller/sellerimage/banana.jpg" alt="top item">
                <h1>Banana from Malda</h1>
            </div>
        </a>
        <!-- Repeat the above structure for other .trending-item divs -->
        <a href="http://localhost:8000/wip/falodhyam/buyer/view_products.php?type=melons">
            <div class="trending-item">
                <img src="../seller/sellerimage/dragonfruit.jpg" alt="top item">
                <h1>Dragonfruit from Lamjung</h1>
            </div>
        </a>
        <!-- Repeat the above structure for other .trending-item divs -->
        <a href="http://localhost:8000/wip/falodhyam/buyer/view_products.php?type=tropical%20fruits">
            <div class="trending-item">
                <img src="../seller/sellerimage/highq.png" alt="top item">
                <h1>Mango from Malda</h1>
            </div>
        </a>
        <!-- Repeat the above structure for other .trending-item divs -->
        <a href="http://localhost:8000/wip/falodhyam/buyer/view_products.php?type=others">
            <div class="trending-item">
                <img src="../seller/sellerimage/sugarcane.webp" alt="top item">
                <h1>Local Sugarcane</h1>
            </div>
        </a>
        <!-- Repeat the above structure for other .trending-item divs -->
    </section>
    <section class="sponsored">
        <div class="banner-img">
            <img src="./assets/imgs/img1.jpg" alt="banner image">
        </div>
        <div class="banner-desc">
            <h1>Bumper offer</h1>
            <p>Inshane discount of <strong>4%</strong> off</p>
            <a href="http://localhost:8000/wip/falodhyam/buyer/view_products.php" class="btn">shop now</a>
        </div>
    </section>

    </section>
    <?php include "./components/_footer.php"; ?>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script>
        //         let toggler = document.getElementById("toggler");
        // let menus = document.getElementsByClassName("navbar");
        // toggler.addEventListener("click", () => {
        //     // sliding all the available menus
        //     for (let num = 0; num < menus.length; i++) {
        //         menus[num].classList.toggle("show");
        //     }
        // });



        // let slideIndex = 0;
        // const slides = document.querySelectorAll(".carousel img");
        // const totalSlides = slides.length;

        // function slideShower() {
        //     for (let i = 0; i < totalSlides; i++) {
        //         slides[i].style.display = "none";
        //     }
        //     slides[slideIndex].style.display = "block";
        // }

        // function nextSlide(){
        //     slideIndex++;
        //     if(slideIndex===totalSlides){
        //         slideIndex = 0;
        //     }
        //     slideShower();
        // }

        // function prevSlide(){
        //     slideIndex--;
        //     if(slideIndex<0){
        //         slideIndex=totalSlides-1;
        //     }
        //     slideShower();
        // }
        // setInterval(nextSlide, 5000);
        // showSlides();


        <?php include "./js/interact.js"; ?>
    </script>
</body>

</html>