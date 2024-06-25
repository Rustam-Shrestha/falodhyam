
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        <?php include "././assets/css/style.css"; ?>
    </style>
    <title>About page</title>
</head>

<body>
    <?php include "./components/_header.php"; ?>
    <?php require("./components/alert.php"); ?>
    <section class="sign-board">
        <div class="about-content">

            <h1>About us</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque, saepe molestias eveniet minima odio
                voluptates suscipit, exercitationem quis dolor voluptatum accusamus delectus! Quas deserunt eius nisi,
                odit ad delectus dignissimos ipsum itaque consequuntur hic sit nostrum, beatae quam consequatur magni
                asperiores non consectetur quis vero, veritatis error rem facilis. Vitae molestiae, neque blanditiis
                provident deserunt error enim? Obcaecati deleniti labore, consequuntur magni assumenda officia itaque
                cupiditate impedit nihil laborum maiores quia suscipit enim vel tempora illum ex dicta velit inventore
                autem voluptatum. Perferendis unde cumque est quo. Facilis earum sint doloremque hic error quaerat
                aspernatur molestiae cupiditate, exercitationem minima possimus quis laudantium deserunt, ipsum quasi
                molestias amet nihil quod quas cum atque dolor enim officiis totam! Dignissimos debitis ratione at quae
                neque ducimus sed atque doloribus quo architecto odit, id saepe cumque numquam vero, rem quia commodi.
                Quisquam, ab quo.</p>
        </div>
    </section>

    <section class="about-detail">
        <div class="about-img">
            <img src="././assets/imgs/img1.jpg" alt="banner image">
        </div>
        <div class="about-desc">
            <h1>Our Vision</h1>
            <p>we have some agenda with our ecommerce platform</p>
            <ul>
           <li>affordable products</li>
           <li>b2b and b2c</li>
           <li>247 support</li>
           <li>service oriented</li>
            </ul>
        </div>
    </section>



    <?php include "./components/_footer.php"; ?>
   
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script>
        <?php include "./js/interact.js"; ?>

    </script>
</body>

</html>