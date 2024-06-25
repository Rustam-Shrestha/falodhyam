<?php include "./components/connection.php"; ?>
<?php
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

        .contact-info{
            width:80%;
            margin: 10px auto;
        }
        .contact-info iframe{
            width:100%;
        }

    </style>
    <title>Contact page</title>
</head>

<body>
    
    <?php include "./components/_header.php"; ?>
    <?php require("./components/alert.php"); ?>
    <section class="sign-board">
        <div class="about-content">

            <h1>Contact us</h1>
            
        </div>
    </section>


    <section class="contact-info">
        <!-- google map embeddings -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1493.9377956299968!2d85.93663880799004!3d27.04335570754254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ec0b6118a2c61d%3A0x986f315fda87c15d!2sBhimsen%20Mandir%2C%20Bishambar%20Ratukhola%20-%2011!5e1!3m2!1sen!2snp!4v1712479571189!5m2!1sen!2snp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <center>

                <h1>Falodhyam Headquarters</h1>
                <p>Ratukhola-11, Bhiman, Dhanusha</p>
                <p>+977 9861473532 | santosh.787402@smc.tu.edu.np</p>
            </center>
    </section>
    <?php include "./components/_footer.php"; ?>
   <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script>
         <?php include "./js/interact.js"; ?>

    </script>
</body>

</html>