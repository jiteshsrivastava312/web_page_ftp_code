<?php
// login.php

session_start();

// Replace with actual credentials validation logic
$valid_username = 'admin';
$valid_password = 'C@3nt@123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        header('Location: select_date.php'); // Redirect to date selection page
        exit;
    } else {
        echo 'Invalid username or password.';
    }
}
?>

