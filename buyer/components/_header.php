<header class="header">
    <a href="home.php">
        <img style="width:40px; height:40px" src="././assets/imgs/logo.jpg" alt="linker" class="logo">
    </a>
    <div class="nav-container">
        <nav class="navbar">
            <a href="home.php">home</a>
            <a href="view_products.php">products</a>
            <a href="orders.php">orders</a>
            <a href="about.php">about us</a>
            <a href="contact.php">contact us</a>
        </nav>
        <div class="wildcard-icons">
            <?php
            // fetching total no of itemsin cart with spwecific email
            $count_wishlist_items = $con->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
            $total_wishlist_items = $count_wishlist_items->rowCount();

            // fetcching total no of items in wishlist
            $count_cart_items = $con->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
            ?>

            <!-- user ko icon vayeko wala  -->
            <!-- open navigator -->
            <a href="cart.php"><i class="bx bx-cart-download" alt="cart icon"><sup
            class="blob"><?= $total_cart_items; ?></sup></i></a>
            <a href="wishlist.php"><i class="bx bx-heart" alt="wushlist icon"></i><sup
            class="blob"><?= $total_wishlist_items; ?></sup></a>
            <i class="bx bx-user" id="user-btn" alt="user icon"></i>
            <div class="icons">
                <button id="toggler"><i class='bx bx-list-plus'></i></button>
            </div>
        </div>
    </div>
</header>

<form method="post">
<div class="modal" id="modal">
    <p id="terminator">&times;</p>
    <?php
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != "") {
            echo '<strong style="font-size:12px;color:white">Email:</strong> <span style="font-size:12px;color:white">'.$_SESSION['user_email'].' </span><br><button type="submit" name="logout" class="logout-btn btn">log out from '.$_SESSION['user_email'].' </button>';
            
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