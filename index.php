<?php
include('./layout.php');
include('./classes/DB.php');
include('./classes/Login.php');
include('./classes/Post.php');
include('./classes/Comment.php');

if (isset($_POST['comment'])) {
        Comment::createComment($_POST['commentbody'], $_GET['postid'], $userid);
}

$followingposts = DB::query('SELECT posts.id, posts.body, posts.likes, users.`username` FROM users, posts, followers
WHERE posts.user_id = followers.user_id
AND users.id = posts.user_id
AND follower_id = :userid
ORDER BY posts.likes DESC;',array(':userid'=>$userid));
echo "</br></br></br>";
foreach ($followingposts as $post) {
	
	echo $post['body']." ~ ".$post['username'];
		
	echo "<form action='index.php?postid=".$post['id']."' method='post'>";
    if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$post['id'], ':userid'=>$userid))){
		echo "<input type='submit' name='like' value='Like'>";
	}else{
		echo "<input type='submit' name='unlike' value='Unlike'>";
	}
	echo "<span>".$post['likes']."likes</span>
		</form>
		<form action='index.php?postid=".$post['id']."' method='post'>
			<textarea name='commentbody' rows='3' cols='50' placeholder='Leave Comments?' ></textarea>
			<input type='submit' name='comment' value='Comment'>
		</form>";
		Comment::displayComments($post['id']);
		echo "
		<hr /></br />";	

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
    <div class="container">
        <h1 align=center>Timeline</h1>
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <ul class="list-group">
                <div class="timelineposts">

                </div>
            </ul>
        </div>
        <div class="col-md-3"></div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" data-aos="fade-up">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">@Francis Hello World!</h4>
                </div>
                <div class="modal-body">
                    <p>Enter your comment below.</p>
                    <textarea></textarea>
                </div>
                <div class="modal-footer">
                    <textarea name='commentbody' rows='3' cols='50' placeholder='Leave Comments?'></textarea>
                    </br><button class="btn btn-primary" type="button">Post Comment</button>
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <nav class="footer-dark navbar-fixed-bottom" style="position: relative">
        <footer>
            <div class="container">
                <p class="copyright">SayHi © 2018</p>
            </div>
        </footer>
    </nav>



    <script type="text/javascript">
        var start = 10;
        var working = false;
        $(window).scroll(function() {
            if ($(this).scrollTop() + 1 >= $('body').height() - $(window).height()) {
                if (working == false) {
                    working = true;
                    $.ajax({
                        type: "GET",
                        url: "api/posts&start=" + start,
                        processData: false,
                        contentType: "application/json",
                        data: '',
                        success: function(r) {
                            var posts = JSON.parse(r)
                            $.each(posts, function(index) {
                                if (posts[index].PostImage == "") {
                                    $('.timelineposts').html(
                                        $('.timelineposts').html() + '<li class="list-group-item" id="' + posts[index].PostId + '"><blockquote><p>' + posts[index].PostBody + '</p><footer align="right">Posted by ' + posts[index].PostedBy + ' on ' + posts[index].PostDate + '</br><div style = "text-align:right;"><button class="btn-like btn-default" data-id="' + posts[index].PostId + '" type="button" style="color:#eb3b60;;background-color:#f2f6fc;"> <i class="glyphicon glyphicon-heart" data-aos="flip-right"></i><span> ' + posts[index].Likes + ' Likes</span></button><button class="btn-comment btn-default comment" type="button" data-postid="' + posts[index].PostId + '" style="color:#eb3b60;;background-color:#f2f6fc;"><i class="glyphicon glyphicon-flash" style="color:#f9d616;"></i><span style="color:#f9d616;"> Comments</span></button></footer></blockquote></li></div>'
                                    )
                                } else {
                                    $('.timelineposts').html(
                                        $('.timelineposts').html() + '<li class="list-group-item" id="' + posts[index].PostId + '"><blockquote><p>' + posts[index].PostBody + '</p><img src="" data-tempsrc="' + posts[index].PostImage + '" class="postimg" id="img' + posts[index].postId + '"><footer align="right">Posted by ' + posts[index].PostedBy + ' on ' + posts[index].PostDate + '</br><div style = "text-align:right;"><button class="btn-like btn-default" data-id="' + posts[index].PostId + '" type="button" style="color:#eb3b60;;background-color:#f2f6fc;"> <i class="glyphicon glyphicon-heart" data-aos="flip-right"></i><span> ' + posts[index].Likes + ' Likes</span></button><button class="btn-comment btn-default comment" type="button" data-postid="' + posts[index].PostId + '" style="color:#eb3b60;;background-color:#f2f6fc;"><i class="glyphicon glyphicon-flash" style="color:#f9d616;"></i><span style="color:#f9d616;"> Comments</span></button></footer></blockquote></li></div>'
                                    )
                                }
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
                                            $("[data-id='" + buttonid + "']").html(' <i class="glyphicon glyphicon-heart" data-aos="flip-right"></i><span> ' + res.Likes + ' Likes</span>')
                                        },
                                        error: function(r) {
                                            console.log(r)
                                        }
                                    });
                                })
                            })

                            $('.postimg').each(function() {
                                this.src = $(this).attr('data-tempsrc')
                                this.onload = function() {
                                    this.style.opacity = '1';
                                }
                            })

                            scrollToAnchor(location.hash)

                            start += 10;
                            setTimeout(function() {
                                working = false;
                            }, 4000)

                        },
                        error: function(r) {
                            console.log(r)
                        }
                    });
                }
            }
        })

        function scrollToAnchor(aid) {
            try {
                var aTag = $(aid);
                $('html,body').animate({
                    scrollTop: aTag.offset().top
                }, 'slow');
            } catch (error) {
                console.log(error)
            }
        }

        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "api/posts&start=0",
                processData: false,
                contentType: "application/json",
                data: '',
                success: function(r) {
                    var posts = JSON.parse(r)
                    $.each(posts, function(index) {
                        if (posts[index].PostImage == "") {
                            $('.timelineposts').html(
                                $('.timelineposts').html() + '<li class="list-group-item" id="' + posts[index].PostId + '"><blockquote><p>' + posts[index].PostBody + '</p><footer align="right">Posted by ' + posts[index].PostedBy + ' on ' + posts[index].PostDate + '</br><div style = "text-align:right;"><button class="btn-like btn-default" data-id="' + posts[index].PostId + '" type="button" style="color:#eb3b60;;background-color:#f2f6fc;"> <i class="glyphicon glyphicon-heart" data-aos="flip-right"></i><span> ' + posts[index].Likes + ' Likes</span></button><button class="btn-comment btn-default comment" type="button" data-postid="' + posts[index].PostId + '" style="color:#eb3b60;;background-color:#f2f6fc;"><i class="glyphicon glyphicon-flash" style="color:#f9d616;"></i><span style="color:#f9d616;"> Comments</span></button></footer></blockquote></li></div>'
                            )
                        } else {
                            $('.timelineposts').html(
                                $('.timelineposts').html() + '<li class="list-group-item" id="' + posts[index].PostId + '"><blockquote><p>' + posts[index].PostBody + '</p><img src="" data-tempsrc="' + posts[index].PostImage + '" class="postimg" id="img' + posts[index].postId + '"><footer align="right">Posted by ' + posts[index].PostedBy + ' on ' + posts[index].PostDate + '</br><div style = "text-align:right;"><button class="btn-like btn-default" data-id="' + posts[index].PostId + '" type="button" style="color:#eb3b60;;background-color:#f2f6fc;"> <i class="glyphicon glyphicon-heart" data-aos="flip-right"></i><span> ' + posts[index].Likes + ' Likes</span></button><button class="btn-comment btn-default comment" type="button" data-postid="' + posts[index].PostId + '" style="color:#eb3b60;;background-color:#f2f6fc;"><i class="glyphicon glyphicon-flash" style="color:#f9d616;"></i><span style="color:#f9d616;"> Comments</span></button></footer></blockquote></li></div>'
                            )
                        }
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
                                    $("[data-id='" + buttonid + "']").html(' <i class="glyphicon glyphicon-heart" data-aos="flip-right"></i><span> ' + res.Likes + ' Likes</span>')
                                },
                                error: function(r) {
                                    console.log(r)
                                }
                            });
                        })
                    })

                    $('.postimg').each(function() {
                        this.src = $(this).attr('data-tempsrc')
                        this.onload = function() {
                            this.style.opacity = '1';
                        }
                    })

                    scrollToAnchor(location.hash)
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