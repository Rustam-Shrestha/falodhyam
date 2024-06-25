<?php
session_start();

$warning_msg = []; // Initialize warning messages

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = "";
}
// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: home.php?loggedin=true");
    exit();
}

include "./components/connection.php";
include "./components/_header.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['yield'])) {
    $id = uniqid();
    $name = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $house_number = filter_var($_POST['house_number'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $cpass = filter_var($_POST['cpassword'], FILTER_SANITIZE_STRING);

    // Check if email already exists
    $query = "SELECT * FROM `buyers` WHERE email = ?";
    $select_user = $con->prepare($query);
    $select_user->execute([$email]);

    if ($select_user->rowCount() > 0) {
        $warning_msg[] = "Email already exists in the database";
    } else {
        if ($pass !== $cpass) {
            $warning_msg[] = "Passwords do not match";
        } else {
            // Hash the password
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            // Insert new user
            $query = "INSERT INTO `buyers` (id, name, email, phone, address, house_number, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insert_user = $con->prepare($query);
            $success = $insert_user->execute([$id, $name, $email, $phone, $address, $house_number, $hashed_pass]);

            if ($success) {
                // Log in the user
                $sqlQuery = "SELECT * FROM `buyers` WHERE email = ? AND phone = ?";
                $select_user = $con->prepare($sqlQuery);
                $select_user->execute([$email, $phone]);
                $row = $select_user->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    $_SESSION['user_id'] = $row["id"];
                    $_SESSION['user_name'] = $row["name"];
                    $_SESSION['user_email'] = $row["email"];
                    header("Location: home.php?loggedin=true");
                    exit();
                }
            } else {
                $warning_msg[] = "Failed to insert user data";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup page</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        <?php include "././assets/css/style.css"; ?>
        <?php include "././assets/css/signup-style.css"; ?>
    </style>
</head>

<body>
    <section class="signup">
        <fieldset>
            <legend>Signup for new account</legend>
            <?php include "./components/alert.php";?>
            <form action="" method="post" id="signup-form">
                
                <p>Username:</p>
                <input type="text" name="username" id="username" placeholder="Enter your name" maxlength="40" required>
                <div id="errorname" style="color: crimson"></div>
                
                <p>Email address:</p>
                <input type="email" name="email" id="email" placeholder="Enter your email" maxlength="40" oninput="this.value = this.value.replace(/\s/g, '')" required>
                <div id="erroremail" style="color: crimson"></div>

                <p>Phone number:</p>
                <input type="text" name="phone" id="phone" placeholder="Enter your phone number" maxlength="10" required>
                <div id="errorphone" style="color: crimson"></div>

                <p>Address:</p>
                <select name="address" id="address" required>
                    <option value="">Select your address</option>
                    <?php
                    $places = ["Balaju", "Sukedhara", "Kalanki", "Samakhusi", "Gongabu", "Thamel", "Baneshwor", "Koteshwor", "Maitidevi", "Lalitpur", "Bhaktapur", "Swayambhu", "Chabahil", "Maharajgunj", "Naxal"];
                    foreach ($places as $place) {
                        echo "<option value=\"$place\">$place</option>";
                    }
                    ?>
                </select>

                <p>House number:</p>
                <input type="text" name="house_number" id="house_number" placeholder="Enter your house number" maxlength="8" required>

                <p>Password:</p>
                <input type="password" name="password" id="password" required />
                <div id="errortext" style="color: crimson"></div>

                <p>Confirm Password:</p>
                <input type="password" name="cpassword" id="cpassword" required />
                <div id="password-warning"></div>
                <br>

                <input type="submit" class="btn" id="submit-button" name="yield">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </form>
        </fieldset>
    </section>
    <?php include "./components/_footer.php"; ?>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script>
        <?php include "./js/validate.js"; ?>
        <?php include "./js/interact.js"; ?>
    </script>
</body>

</html>
