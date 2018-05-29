<?php
include('./layout.html');
include('./classes/DB.php');
include('./classes/Login.php');
if (!Login::isLoggedIn()) {
        die("Not logged in.");
}
if (isset($_POST['confirm'])) {
        if (isset($_POST['alldevices'])) {
                DB::query('DELETE FROM login_tokens WHERE user_id=:userid', array(':userid'=>Login::isLoggedIn()));
        } else {
                if (isset($_COOKIE['SNID'])) {
                        DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));
                }
                
        }
        document.cookie = "SNID" + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        document.cookie = "SNID_" + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SayHi</title>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="./js/jquery.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="./js/bs-animation.js"></script>
	<script src="./js/sayhi.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>
</head>
<body>
<div layout="layout.html"></div>
<h1>Logout of your Account?</h1>
<p>Are you sure you'd like to logout?</p>
<form action="logout.php" method="post">
        <input type="checkbox" name="alldevices" value="alldevices"> Logout of all devices?<br />
        <input type="submit" name="confirm" value="Confirm">
</form>
<nav class="footer-dark navbar-fixed-bottom" style="position: absulate">
        <footer>
            <div class="container">
                <p align=center>This Website is for showing my ability only.</p>
                <p class="copyright">SayHi © 2018</p>
            </div>
        </footer>
    </nav>
<script type="text/javascript">
        includeHTML();
</script>
</body>

</html>