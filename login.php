<?php
include_once("nav.php");
?>

<h1>Login</h1>
<section id="loginSection">
    <form action="http://localhost/movies/login/" method="post">
        <div class="container">
            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>

            <button type="submit" id="loginBtn">Login</button>
        </div>
    </form>
</section>

<?php
include_once("footer.php");
?>