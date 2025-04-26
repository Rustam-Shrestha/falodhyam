<?php
session_start();
require 'component/dbconnect.php'; // Ensure database connection is included

// Check if the user is already logged in
if (isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['name']);
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];
    $confirmpass = $_POST['cpassword'];
    
    // Validate Input
    if (strlen($username) < 4) {
        echo '<script>alert("Username must be at least 4 characters long.");</script>';
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email format.");</script>';
        exit;
    }

    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $password)) {
        echo '<script>alert("Password must have at least 8 characters, 1 uppercase, 1 lowercase, 1 number, and 1 special character.");</script>';
        exit;
    }

    if ($password !== $confirmpass) {
        echo '<script>alert("Passwords do not match.");</script>';
        exit;
    }

    // Check if Email Already Exists
    $checkemail = $conn->prepare("SELECT * FROM `seller` WHERE `s-email` = ?");
    $checkemail->execute([$email]);

    if ($checkemail->rowCount() > 0) {
        echo '<script>alert("User email already exists.");</script>';
        exit;
    }

    // Handle File Upload (Pan Card)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $file_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_ext)) {
            echo '<script>alert("Invalid file type. Only JPG, PNG, and GIF are allowed.");</script>';
            exit;
        }

        if ($image_size > 5 * 1024 * 1024) { // 5MB limit
            echo '<script>alert("File size exceeds 5MB limit.");</script>';
            exit;
        }

        $upload_dir = "img/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $new_filename = uniqid("pan_") . '.' . $file_ext;
        $image_folder = $upload_dir . $new_filename;

        if (!move_uploaded_file($image_tmp_name, $image_folder)) {
            echo '<script>alert("Error: Failed to move uploaded file. Check folder permissions.");</script>';
            exit;
        }
    } else {
        echo '<script>alert("Error: File upload failed. Error Code: ' . $_FILES['image']['error'] . '");</script>';
        exit;
    }

    // Hash the Password
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    // Insert Data into Database
    $sql = $conn->prepare("INSERT INTO `seller`(`s-name`, `s-email`, `s-password`, `s-pan_card`) VALUES(?, ?, ?, ?)");

    if ($sql->execute([$username, $email, $hashed_pass, $new_filename])) {
        echo '<script>alert("Registration successful! Redirecting to login..."); window.location.href="login.php";</script>';
    } else {
        echo '<script>alert("Failed to register. Please try again.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="one.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-size: 20px; }
        :root { --green: #7cab05; --box-shadow: 0 0 10px rgba(0 0 0/15%); }
        body { background-image: url('img/body-bg.jpg'); width: 100%; }
        .main { display: flex; padding: 30px 20px; justify-content: center; margin: 8rem; background: #fff; box-shadow: 0 10px 18px 0 rgba(0 0 0/10%); }
        section { display: flex; width: 70%; flex-wrap: wrap; margin: 20px; box-shadow: 0 10px 18px 0 rgba(0 0 0/10%); background: #fff; }
        label { color: var(--green); display: block; text-transform: capitalize; }
        h1 { font-size: 30px; text-align: center; margin: 12px; }
        .btn { transition: 1.0s background; margin: 20px auto; box-shadow: 0 5px 10px rgba(0,0,0,0.1); border: none; width: 25%; border-radius: 12px; cursor: pointer; padding: 8px; }
        input { width: 100%; outline: none; line-height: 20px; font-size: 20px; }
        form { width: 100%; padding: 20px; }
        p, a { text-transform: capitalize; text-align: center; }
        a { color: red; text-decoration: none; }
        @media(max-width:991px) { .main { margin: 2rem; } section { width: 100%; } .btn { width: 40%; font-size: 18px; } }
    </style>
</head>
<body>
    <div class="main">
        <section>
            <form action="" method="post" enctype="multipart/form-data">
                <h1>Register</h1>
                <label>User Name <sup>*</sup></label>
                <input type="text" name="name" maxlength="20" placeholder="Enter your username" required>
                <label>User Email</label>
                <input type="email" name="email" maxlength="26" placeholder="Enter your email" required>
                <label>User Password</label>
                <input type="password" name="password" maxlength="20" placeholder="Enter your password" required>
                <label>Confirm Password</label>
                <input type="password" name="cpassword" maxlength="20" placeholder="Confirm password" required>
                <label>Select Your Pan Card <sup>*</sup></label>
                <input type="file" name="image" accept="image/*" required>
                <input type="submit" name="register" class="btn" value="Register Now">
                <p>Already have an account? <a href="login.php">Login now</a></p>
                <p>Are you a buyer? <a href="../buyer/home.php">Buyer homepage</a></p>
            </form>
        </section>
    </div>
</body>
</html>
