<?php
require_once("DB.php");
require_once("Mail.php");

//database setting
$db = new DB("127.0.0.1", "socialnetwork", "bear", "shuzi");

//get requests
if ($_SERVER['REQUEST_METHOD'] == "GET") {
	//return all users that has sent a message to the current user
        if ($_GET['url'] == "musers") {
                $token = $_COOKIE['SNID'];
                $userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                $users = $db->query("SELECT DISTINCT s.username AS Sender, r.username AS Receiver, s.id AS SenderID, r.id AS ReceiverID FROM messages LEFT JOIN users s ON s.id = messages.sender LEFT JOIN users r ON r.id = messages.receiver WHERE (s.id = :userid OR r.id=:userid)", array(":userid"=>$userid));
                $u = array();
                foreach ($users as $user) {
                        if (!in_array(array('username'=>$user['Receiver'], 'id'=>$user['ReceiverID']), $u)) {
                                array_push($u, array('username'=>$user['Receiver'], 'id'=>$user['ReceiverID']));
                        }
                        if (!in_array(array('username'=>$user['Sender'], 'id'=>$user['SenderID']), $u)) {
                                array_push($u, array('username'=>$user['Sender'], 'id'=>$user['SenderID']));
                        }
                }
                echo json_encode($u);
        } else if ($_GET['url'] == "auth") {
        } else if ($_GET['url'] == "search") {
		//searchbar
                $tosearch = explode(" ", $_GET['query']);
                if (count($tosearch) == 1) {
                        $tosearch = str_split($tosearch[0], 2);
                }
                $whereclause = "";
                $paramsarray = array(':body'=>'%'.$_GET['query'].'%');
                for ($i = 0; $i < count($tosearch); $i++) {
                        if ($i % 2) {
                        $whereclause .= " OR body LIKE :p$i ";
                        $paramsarray[":p$i"] = $tosearch[$i];
                        }
                }
                $posts = $db->query('SELECT posts.id, posts.body, users.username, posts.posted_at FROM posts, users WHERE users.id = posts.user_id AND posts.body LIKE :body '.$whereclause.' LIMIT 10', $paramsarray);
                //echo "<pre>";
                echo json_encode($posts);
        } else if ($_GET['url'] == "messages") {
		//return all related messages
                $sender = $_GET['sender'];
                $token = $_COOKIE['SNID'];
                $receiver = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                $messages = $db->query('SELECT messages.id, messages.body, s.username AS Sender, r.username AS Receiver
                FROM messages
                LEFT JOIN users s ON messages.sender = s.id
                LEFT JOIN users r ON messages.receiver = r.id
                WHERE (r.id=:r AND s.id=:s) OR (r.id=:s AND s.id=:r)', array(':r'=>$receiver, ':s'=>$sender));
                echo json_encode($messages);
        }else if ($_GET['url'] == "users") {
		//get the current username
                $token = $_COOKIE['SNID'];
                $user_id = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                $username = $db->query('SELECT username FROM users WHERE id=:uid', array(':uid'=>$user_id))[0]['username'];
                echo $username;
        } else if ($_GET['url'] == "comments" && isset($_GET['postid'])) {
		//get all comment for current post
                $output = "";
                $comments = $db->query('SELECT comments.comment, users.username FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id', array(':postid'=>$_GET['postid']));
                $output .= "[";
                foreach($comments as $comment) {
                        $output .= "{";
                        $output .= '"Comment": "'.$comment['comment'].'",';
                        $output .= '"CommentedBy": "'.$comment['username'].'"';
                        $output .= "},";
                        //echo $comment['comment']." ~ ".$comment['username']."<hr />";
                }
                if($output == "["){
                        $output .= '{"Comment": "Ops! It is empty~","CommentedBy": "SayHi"},';
                }
                $output = substr($output, 0, strlen($output)-1);
                $output .= "]";
                echo $output;
        }else if ($_GET['url'] == "posts") {
		//get all posts relatied to the login user
                $start = (int)$_GET['start'];
                $token = $_COOKIE['SNID'];
                $userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                $followingposts = $db->query('SELECT DISTINCT posts.id, posts.body, posts.posted_at, posts.postimg, posts.likes, users.`username` FROM users, posts, followers
                WHERE (posts.user_id = followers.user_id
                OR posts.user_id = :userid)
                AND users.id = posts.user_id
                AND follower_id = :userid
                ORDER BY posts.likes DESC
                LIMIT 10 OFFSET '.$start.';', array(':userid'=>$userid), array(':userid'=>$userid));
                $response = "[";
                foreach($followingposts as $post) {
                        $response .= "{";
                                $response .= '"PostId": '.$post['id'].',';
                                $response .= '"PostBody": "'.$post['body'].'",';
                                $response .= '"PostedBy": "'.$post['username'].'",';
                                $response .= '"PostDate": "'.$post['posted_at'].'",';
                                $response .= '"PostImage":"'.$post['postimg'].'",';
                                $response .= '"Likes": '.$post['likes'].'';
                        $response .= "},";
                }
                $response = substr($response, 0, strlen($response)-1);
                $response .= "]";
                http_response_code(200);
                echo $response;
        }else if ($_GET['url'] == "profileposts") {
		//get all posts created by the user of the profile
                $start = (int)$_GET['start'];
                $userid = $db->query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
                $followingposts = $db->query('SELECT posts.id, posts.body, posts.posted_at, posts.postimg, posts.likes, users.`username` FROM users, posts
                WHERE users.id = posts.user_id
                AND users.id = :userid
                ORDER BY posts.posted_at DESC
		LIMIT 5 OFFSET '.$start.';', array(':userid'=>$userid));
                $response = "[";
                foreach($followingposts as $post) {
                        $response .= "{";
                                $response .= '"PostId": '.$post['id'].',';
                                $response .= '"PostBody": "'.$post['body'].'",';
                                $response .= '"PostedBy": "'.$post['username'].'",';
                                $response .= '"PostDate": "'.$post['posted_at'].'",';
                                $response .= '"PostImage":"'.$post['postimg'].'",';
                                $response .= '"Likes": '.$post['likes'].'';
                        $response .= "},";
                }
                $response = substr($response, 0, strlen($response)-1);
                $response .= "]";
                http_response_code(200);
                echo $response;
        }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
	//post request
        if ($_GET['url'] == "message") {
		//post a message
                if (isset($_COOKIE['SNID'])) {
                        $token = $_COOKIE['SNID'];
                } else {
                        die();
                }
                $userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                $postBody = file_get_contents("php://input");
                $postBody = json_decode($postBody);
                $body = $postBody->body;
                $receiver = $postBody->receiver;
                if (strlen($body) > 100) {
                        echo "{ 'Error': 'Message too long!' }";
                }
                $db->query("INSERT INTO messages VALUES (null, :body, :sender, :receiver, '0')", array(':body'=>$body, ':sender'=>$userid, ':receiver'=>$receiver));
                echo '{ "Success": "Message Sent!" }';

        } else if ($_GET['url'] == "users") {
		//create a user
                $postBody = file_get_contents("php://input");
                $postBody = json_decode($postBody);
                $username = $postBody->username;
                $email = $postBody->email;
                $password = $postBody->password;
                if (!$db->query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
                        if (strlen($username) >= 3 && strlen($username) <= 32) {
                                if (preg_match('/[a-zA-Z0-9_]+/', $username)) {
                                        if (strlen($password) >= 6 && strlen($password) <= 60) {
                                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                        if (!$db->query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {
                                                $db->query('INSERT INTO users VALUES (null, :username, :password, :email, \'0\', null)', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
                                                Mail::sendMail('Welcome to our Social Network!', 'Your account has been created!', $email);
                                                echo '{ "Success": "User Created!" }';
                                                http_response_code(200);
                                        } else {
                                                echo '{ "Error": "Email in use!" }';
                                                http_response_code(409);
                                        }
                                } else {
                                        echo '{ "Error": "Invalid Email!" }';
                                        http_response_code(409);
                                        }
                                } else {
                                        echo '{ "Error": "Invalid Password!" }';
                                        http_response_code(409);
                                }
                                } else {
                                        echo '{ "Error": "Invalid Username!" }';
                                        http_response_code(409);
                                }
                        } else {
                                echo '{ "Error": "Invalid Username!" }';
                                http_response_code(409);
                        }
                } else {
                        echo '{ "Error": "User exists!" }';
                        http_response_code(409);
                }
        }else if ($_GET['url'] == "auth") {
		//login
                $postBody = file_get_contents("php://input");
                $postBody = json_decode($postBody);
                $username = $postBody->username;
                $password = $postBody->password;
                if ($db->query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
                        if (password_verify($password, $db->query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) {
                                $cstrong = True;
                                $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                                $user_id = $db->query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
                                $db->query('INSERT INTO login_tokens VALUES (null, :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                                setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
                                setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
                                echo ''.$token.'';
                        } else {
                                echo '{ "Error": "Invalid username or password!" }';
                                http_response_code(401);
                        }
                } else {
                        echo '{ "Error": "Invalid username or password!" }';
                        http_response_code(401);
                }
        }else if ($_GET['url'] == "likes") {
		//post likes
                $postId = $_GET['id'];
                $token = $_COOKIE['SNID'];
                $likerId = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                if (!$db->query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId))) {
                        $db->query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postId));
                        $db->query('INSERT INTO post_likes VALUES (null, :postid, :userid)', array(':postid'=>$postId, ':userid'=>$likerId));
                        //Notify::createNotify("", $postId);
                } else {
                        $db->query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$postId));
                        $db->query('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId));
                }
                echo "{";
                echo '"Likes":';
                echo $db->query('SELECT likes FROM posts WHERE id=:postid', array(':postid'=>$postId))[0]['likes'];
                echo "}";
        }else if ($_GET['url'] == "comments" && isset($_GET['id']) && isset($_GET['commentbody'])) {
		//write comments to a post
                $postId = $_GET['id'];
                $token = $_COOKIE['SNID'];
                $userId = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                $commentBody = $_GET['commentbody'];
                if(strlen($commentBody) < 1){
			die('Please Enter Something Before Post!');
		}
		
		if(strlen($commentBody) > 200){
			die('Your Post is Too Long!');
		}
		
                if(!$db->query('SELECT id FROM posts WHERE id=:postid', array(':postid'=>$postId))){
			echo 'Invalid post ID';
		}else if($db->query('SELECT id FROM comments WHERE comment=:comment AND user_id=:userid AND post_id = :postid', array(':comment'=>$commentBody, ':userid'=>$userId, ':postid'=>$postId))){
                }else{
			$db->query('INSERT INTO comments VALUE (null, :comment, :userid, NOW(), :postid)', array(':comment'=>$commentBody, ':userid'=>$userId, ':postid'=>$postId));
		}
                $output = "";
                $comments = $db->query('SELECT DISTINCT comments.comment, users.username FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id', array(':postid'=>$postId));
                $output .= "[";
                foreach($comments as $comment) {
                        $output .= "{";
                        $output .= '"Comment": "'.$comment['comment'].'",';
                        $output .= '"CommentedBy": "'.$comment['username'].'"';
                        $output .= "},";
                        //echo $comment['comment']." ~ ".$comment['username']."<hr />";
                }
                if($output == "["){
                        $output .= '{"Comment": "Oh! It is empty~","CommentedBy": "SayHi"},';
                }
                $output = substr($output, 0, strlen($output)-1);
                $output .= "]";
                echo $output;
        }else if($_GET['url'] == "logout"){
		//logout
                $token = $_COOKIE['SNID'];
                $userId = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                if (isset($_POST['alldevices'])) {
                        $db->query('DELETE FROM login_tokens WHERE user_id=:userid', array(':userid'=>$userId));
                } else {
                        if (isset($_COOKIE['SNID'])) {
                                $db->query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));
                        }
                        
                }
                unsetcookie("SNID");
                unsetcookie("SNID_");
        }

}  else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
        if ($_GET['url'] == "auth") {
                if (isset($_GET['token'])) {
                        if ($db->query("SELECT token FROM login_tokens WHERE token=:token", array(':token'=>sha1($_GET['token'])))) {
                                $db->query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_GET['token'])));
                                echo '{ "Status": "Success" }';
                                http_response_code(200);
                        } else {
                                echo '{ "Error": "Invalid token" }';
                                http_response_code(400);
                        }
                } else {
                        echo '{ "Error": "Malformed request" }';
                        http_response_code(400);
                }
        }
} else {
        http_response_code(405);
}
?>
