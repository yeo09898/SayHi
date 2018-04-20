<?php
class Post{
	
	public static function createPost($postbody, $loggedInUserId, $profileUserId){	
		if(strlen($postbody) < 1){
			die('Please Enter Something Before Post!');
		}
		
		if(strlen($postbody) > 200){
			die('Your Post is Too Long!');
		}
		
		$topics = self::getTopics($postbody);
		
        if ($loggedInUserId == $profileUserId) {
			if (count(self::notify($postbody)) != 0) {
                foreach (self::notify($postbody) as $key => $n) {
                    $s = $loggedInUserId;
                    $r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];
                    if ($r != 0) {
                        DB::query('INSERT INTO notifications VALUES (null, :type, :receiver, :sender)', array(':type'=>$n, ':receiver'=>$r, ':sender'=>$s));
                    }
                }
        }
						
			DB::query('INSERT INTO posts VALUES (null, :postbody, NOW(), :userid, 0, null, :topics)', array(':postbody'=>$postbody, ':userid'=>$profileUserId, ':topics'=>$topics));
        } else {
            die('Incorrect user!');
        }
	}
	
	public static function createImgPost($postbody, $loggedInUserId, $profileUserId, $image){	
		
		if(strlen($postbody) > 200){
			die('Your Post is Too Long!');
		}
		
		$topics = self::getTopics($postbody);
		
        if ($loggedInUserId == $profileUserId) {
			DB::query('INSERT INTO posts VALUES (null, :postbody, NOW(), :userid, 0, null, :topics)', array(':postbody'=>$postbody, ':userid'=>$profileUserId, ':topics'=>$topics));
			$postid = DB::query('SELECT id FROM posts WHERE user_id=:userid ORDER BY id DESC LIMIT 1',array(':userid'=>$loggedInUserId))[0]['id'];
			return $postid;
		} else {
            die('Incorrect user!');
        }
	}
	
	public static function likePost($postId, $likerId){
		if (!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId))) {
            DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postId));
            DB::query('INSERT INTO post_likes VALUES (null, :postid, :userid)', array(':postid'=>$postId, ':userid'=>$likerId));
		} else {
            DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$postId));
            DB::query('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postId, ':userid'=>$likerId));
		}
	}
	
	public static function getTopics($text){
		
		$text = explode(" ",$text);
		$topics = "";		
		foreach ($text as $word){
			if(substr($word, 0, 1) == "#"){
				$topics.= substr($word, 1).",";
			}
		}
		return $topics;
	}
	
	public static function notify($text) {
        $text = explode(" ", $text);
        $notify = array();
        foreach ($text as $word) {
            if (substr($word, 0, 1) == "@") {
                $notify[substr($word, 1)] = 1;
            }
        }
        return $notify;
    }
	
	public static function link_add($text){
		
		$text = explode(" ",$text);
		$newstring = "";
		
		foreach ($text as $word){
			if(substr($word,0,1) == "@"){
				$newstring.= "<a href='profile.php?username=".substr($word, 1)."'>".htmlspecialchars($word)." </a>";
			} else if(substr($word,0,1) == "#"){
				$newstring.= "<a href='topics.php?topic=".substr($word, 1)."'>".htmlspecialchars($word)." </a>";
			} else {
				$newstring.= htmlspecialchars($word)." ";
			}
		}
		return $newstring;
	}
	
	
	
	public static function displayPosts($userid, $username, $loggedInUserId){
		
		$dbposts = DB::query('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid'=>$userid));
        $posts = "";
        foreach($dbposts as $p) {
			if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$p['id'], ':userid'=>$loggedInUserId))){
				$posts .= "<img src='".$p['postimg']."'>".self::link_add($p['body'])."
				<form action='profile.php?username=$username&postid=".$p['id']."' method='post'>
                <input type='submit' name='like' value='Like'>
				<span>".$p['likes']."likes</span>
				";
				if($userid == $loggedInUserId){
					$posts .= "<input type='submit' name='deletepost' value='x' />";
				}
				$posts .= "
				</form><hr /></br />
				";
			}else{
				$posts .= "<img src='".$p['postimg']."'>".self::link_add($p['body'])."
				<form action='profile.php?username=$username&postid=".$p['id']."' method='post'>
                <input type='submit' name='unlike' value='Unlike'>
				<span>".$p['likes']."likes</span>
				";
				if($userid == $loggedInUserId){
					$posts .= "<input type='submit' name='deletepost' value='Delete' />";
				}
				$posts .= "
				</form><hr /></br />
				";
			}
        }
		
		return $posts;
	}
}
?>