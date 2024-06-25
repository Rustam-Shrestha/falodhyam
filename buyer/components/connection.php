<?php
// Define database connection parameters
$dbname = 'mysql:host=localhost;dbname=falodhyam_parties';
$user = 'root';
$pass = '';

// Create a PDO connection
try {
    $con = new PDO($dbname, $user, $pass);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit(); // Terminate script if connection fails
}

// Function to generate unique ID
function uniq_id() {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength = strlen($chars);
    $randomString = "";
    for ($i = 0; $i < 20; $i++) {
        $randomString .= $chars[mt_rand(0, $charLength - 1)];
    }
    return $randomString;
}
?>
