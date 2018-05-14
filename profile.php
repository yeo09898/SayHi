<?php
include('./classes/DB.php');
include('./classes/Login.php');
include('./classes/Post.php');
include('./classes/Image.php');
include('./classes/Notify.php');

$username = "";
$verified = False;
$isFollowing = False;

if (isset($_GET['username'])) {
        if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))) {
                $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
                $userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
                $verified = DB::query('SELECT verified FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['verified'];
                $followerid = Login::isLoggedIn();
                if (isset($_POST['follow'])) {
                        if ($userid != $followerid) {
                                if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                                        if ($followerid == 6) {
                                                DB::query('UPDATE users SET verified=1 WHERE id=:userid', array(':userid'=>$userid));
                                        }
                                        DB::query('INSERT INTO followers VALUES (null, :userid, :followerid)', array(':userid'=>$userid, ':followerid'=>$followerid));
                                } else {
                                        echo 'Already following!';
                                }
                                $isFollowing = True;
                        }
                }
                if (isset($_POST['unfollow'])) {
                        if ($userid != $followerid) {
                                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                                        if ($followerid == 6) {
                                                DB::query('UPDATE users SET verified=0 WHERE id=:userid', array(':userid'=>$userid));
                                        }
                                        DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid));
                                }
                                $isFollowing = False;
                        }
                }
                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid))) {
                        //echo 'Already following!';
                        $isFollowing = True;
                }
				
				if(isset($_POST['deletepost'])){
					if(DB::query('SELECT id FROM posts WHERE id=:postid AND user_id=:userid', array(':postid'=>$_GET['postid'], ':userid'=>$followerid))){
						DB::query('DELETE FROM posts WHERE id=:postid AND user_id=:userid',array(':postid'=>$_GET['postid'],':userid'=>$followerid));
						DB::query('DELETE FROM post_likes WHERE post_id=:postid', array(':postid'=>$_GET['postid']));
                        echo 'Post deleted!';
					}
				}
				
                if (isset($_POST['post'])) {
					if($_FILES['postimg']['size'] == 0){
						Post::createPost($_POST['postbody'], Login::isLoggedIn(), $userid);
					}else{
						$postid = Post::createImgPost($_POST['postbody'], Login::isLoggedIn(), $userid, $_FILES);
						Image::uploadImage('postimg', "UPDATE posts SET postimg=:postimg WHERE id=:postid",array(':postid'=>$postid));
					}
				}
				if(isset($_GET['postid']) && !isset($_POST['deletepost'])){
					Post::likePost($_GET['postid'], $followerid);
				}
				
                $posts = Post::displayPosts($userid, $username, $followerid);
				
        } else {
                die('User not found!');
        }
}
?>
<!-- <form action="profile.php?username=<?php echo $username; ?>" method="post" enctype="multipart/form-data">
        <textarea name="postbody" rows="8" cols="80"></textarea>
        <br />Upload an image:
        <input type="file" name="postimg">
        <input type="submit" name="post" value="Post">
</form> -->
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
    <div class="container">
        <h1><?php echo $username; ?>'s Profile <?php if ($verified) { echo '<i class="glyphicon glyphicon-ok-sign verified" data-toggle="tooltip" title="Verified User" style="font-size:28px;color:#da052b;"></i>'; } ?></h1></div>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <ul class="list-group">
                        <li class="list-group-item"><span><strong>About Me</strong></span>
                            <p>Welcome to my profile bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;</p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group">
                            <div class="timelineposts">

                            </div>
                    </ul>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-default" type="button" style="width:100%;background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:0;outline:none;">NEW POST</button>
                    <ul class="list-group"></ul>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" style="padding-top:100px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                    <h4 class="modal-title">Comments</h4></div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto">
                    <p>The content of your modal.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
		includeHTML();
        $(document).ready(function() {
                $.ajax({
                        type: "GET",
                        url: "api/profileposts?username=<?php echo $username; ?>",
                        processData: false,
                        contentType: "application/json",
                        data: '',
                        success: function(r) {
                                var posts = JSON.parse(r)
                                $.each(posts, function(index) {
                                        $('.timelineposts').html(
                                                $('.timelineposts').html() + '<li class="list-group-item"><blockquote><p>'+posts[index].PostBody+'</p><footer align="right">Posted by '+posts[index].PostedBy+' on '+posts[index].PostDate+'</br><div style = "text-align:right;"><button class="btn-like btn-default" data-id="'+posts[index].PostId+'" type="button" style="color:#eb3b60;;background-color:#f2f6fc;"> <i class="glyphicon glyphicon-heart" data-aos="flip-right"></i><span> '+posts[index].Likes+' Likes</span></button><button class="btn-comment btn-default comment" type="button" data-postid="'+posts[index].PostId+'" style="color:#eb3b60;;background-color:#f2f6fc;"><i class="glyphicon glyphicon-flash" style="color:#f9d616;"></i><span style="color:#f9d616;"> Comments</span></button></footer></blockquote></li></div>'
                                        )
                                        $('[data-postid]').click(function() {
                                                var buttonid = $(this).attr('data-postid');
                                                $.ajax({
                                                        type: "GET",
                                                        url: "api/comments?postid=" + $(this).attr('data-postid'),
                                                        processData: false,
                                                        contentType: "application/json",
                                                        data: '',
                                                        success: function(r) {
                                                                var res = JSON.parse(r)
                                                                showCommentsModal(res);
                                                        },
                                                        error: function(r) {
                                                                console.log(r)
                                                        }
                                                });
                                        });
                                        $('[data-id]').click(function() {
                                                var buttonid = $(this).attr('data-id');
                                                $.ajax({
                                                        type: "POST",
                                                        url: "api/likes?id=" + $(this).attr('data-id'),
                                                        processData: false,
                                                        contentType: "application/json",
                                                        data: '',
                                                        success: function(r) {
                                                                var res = JSON.parse(r)
                                                                $("[data-id='"+buttonid+"']").html(' <i class="glyphicon glyphicon-heart" data-aos="flip-right"></i><span> '+res.Likes+' Likes</span>')
                                                        },
                                                        error: function(r) {
                                                                console.log(r)
                                                        }
                                                });
                                        })
                                })
                        },
                        error: function(r) {
                                console.log(r)
                        }
                });
        });
        function showCommentsModal(res) {
                $('.modal').modal('show')
                var output = "";
                for (var i = 0; i < res.length; i++) {
                        output += res[i].Comment;
                        output += " ~ ";
                        output += res[i].CommentedBy;
                        output += "<hr />";
                }
                $('.modal-body').html(output)
        }
    </script>
</body>

</html>