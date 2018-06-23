<?php
include('./layout.php');
include('./classes/DB.php');
include('./classes/Login.php');
if (Login::isLoggedIn()) {
        $userid = Login::isLoggedIn();
} else {
        echo 'Not logged in!<hr /></br />';
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
        <script src="./js/bs-animation.js"></script>
	<script src="./js/sayhi.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</head>

<body>
	<div layout="layout.html"></div>
        <div class="container">
        <h1>Notifications </h1>
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                      <?php
                      if (DB::query('SELECT * FROM notifications WHERE receiver=:userid', array(':userid'=>$userid))) {
                              $notifications = DB::query('SELECT * FROM notifications WHERE receiver=:userid ORDER BY id DESC', array(':userid'=>$userid));
                              foreach($notifications as $n) {
                                      if ($n['type'] == 1) {
                                              $senderName = DB::query('SELECT username FROM users WHERE id=:senderid', array(':senderid'=>$n['sender']))[0]['username'];
                                              if ($n['extra'] == "") {
                                                      echo "You got a notification!<hr />";
                                              } else {
                                                      $extra = json_decode($n['extra']);
                                                      echo '<li class="list-group-item"><span>'.$senderName." mentioned you in a post! - ".$extra->postbody.'</span></li>';
                                              }
                                      } else if ($n['type'] == 2) {
                                              $senderName = DB::query('SELECT username FROM users WHERE id=:senderid', array(':senderid'=>$n['sender']))[0]['username'];
                                              echo '<li class="list-group-item"><span>'.$senderName.' liked your post!</span></li>';
                                      }
                              }
                      }
                      ?>
                    </ul>
                </div>
            </div>
        </div>
	<nav class="footer-dark navbar-fixed-bottom" style="position: absolute">
        <footer class="page-footer font-small stylish-color-dark pt-4 mt-4">
            <div class="container text-center text-md-left">
                <div class="row">
                    <div class="col-md-2 mx-auto"></div>
                    <div class="col-md-8 mx-auto">
                        <p align=center>This Website is for showing my ability only.</p>
                        <p align=center>Go to other's profile page to follow other users, you can do it by search posts in the search bar.</p>
                        <p align=center>Or you can use sh.yeo09898.com/profile.php?username=the user you want to see.</p><p align=center>Users recommend: Shenghong, bear</p><p align=center>change password is banned for now</p>
                        <p align=center>To test the messages system please use 'Shenghong' and 'bear' both password would be 123456.</p>
                        <p class="copyright"></p>
                    </div>
                    <div class="col-md-2 mx-auto"></div>
                </div>
                <div class="footer-copyright text-center py-3">SayHi © 2018:
                </div>
        </footer>
    	</nav>
    	<script type="text/javascript">
		includeHTML();
	</script>
</body>

</html>