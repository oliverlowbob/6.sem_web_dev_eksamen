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
    <script src="../js/auth.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <title>Movie Database</title>
</head>

<body>
<h1>Sign up</h1>
<section id="signupSection">
    <p hidden>This is a hack for signup check</p>
    <p>Fields marked with (*) are required</p>
    <form id="signupForm">
        <div class="container">
            <label for="firstName" class="nameField">First Name: (*)</label>
            <input type="text" placeholder="Enter First Name" name="firstName" class="inputField" required><br>
            <label for="lastName" class="nameField">Last Name: (*)</label>
            <input type="text" placeholder="Enter Last Name" name="lastName" class="inputField" required><br>
            <label for="password" class="nameField">Password: (*)</label>
            <input type="password" placeholder="Enter Password" name="password" class="inputField" required><br>
            <label for="email" class="nameField">Email: (*)</label>
            <input type="text" placeholder="Enter Email" name="email" class="inputField" required><br>
            <label for="company" class="nameField">Company Name:</label>
            <input type="text" placeholder="Enter Company Name" name="company" class="inputField"><br>
            <label for="address" class="nameField">Address:</label>
            <input type="text" placeholder="Enter Address" name="address" class="inputField"><br>
            <label for="city" class="nameField">City:</label>
            <input type="text" placeholder="Enter City" name="city" class="inputField"><br>
            <label for="state" class="nameField">State:</label>
            <input type="text" placeholder="Enter State" name="state" class="inputField"><br>
            <label for="country" class="nameField">Country:</label>
            <input type="text" placeholder="Enter Country" name="country" class="inputField"><br>
            <label for="postalCode" class="nameField">Postal Code:</label>
            <input type="text" placeholder="Enter Postal Code" name="postalCode" class="inputField"><br>
            <label for="phone" class="nameField">Phone Number:</label>
            <input type="text" placeholder="Enter Phone Number" name="phone" class="inputField"><br>
            <label for="fax" class="nameField">Fax Number:</label>
            <input type="text" placeholder="Enter Fax Number" name="fax" class="inputField"><br>
            <button type="submit" id="signupBtn">Sign Up</button>
        </div>
    </form>
    <a href="../views/login.php">Already a user? Log in</a>
</section>

<?php
include_once("footer.php");
?>