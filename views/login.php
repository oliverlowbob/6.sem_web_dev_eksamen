<?php
include_once("nav.php");

session_start();

if (isset($_SESSION['email'])) 
{
    header('Location: ../views/frontpage.php');
}
?>

<h1>Login</h1>
<section id="loginSection">
    <form action="http://localhost/music/login/" method="post">
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