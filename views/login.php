<?php
session_start();

if (isset($_SESSION['email'])) 
{
    header('Location: ../views/frontpage.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
    </style>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/login.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <title>Movie Database</title>
</head>

<body>
<h1>Login</h1>
<p hidden>This is a paragraph for login check</p>
<section id="loginSection">
    <form id="loginForm">
        <div class="container">
            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" required>
            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>
            <button type="submit" id="loginBtn">Login</button>
        </div>
    </form>
</section>

<?php
include_once("footer.php");
?>