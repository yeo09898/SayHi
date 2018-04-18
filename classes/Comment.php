<?php
class Comment{
	public static function createComment($commentBody, $postId, $userId){	
		if(strlen($commentBody) < 1){
			die('Please Enter Something Before Post!');
		}
		
		if(strlen($commentBody) > 200){
			die('Your Post is Too Long!');
		}
		
        if(!DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid'=>$postId))){
			echo 'Invalid post ID';
		}else{
			DB::query('INSERT INTO comments VALUE (null, :comment, :userid, NOW(), :postid)', array(':comment'=>$commentBody, ':userid'=>$userId, ':postid'=>$postId));
		}
	}
	
	public static function displayComments($postId){
		
		$comments = DB::query('SELECT comments.comments, users.username FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id', array(':postid'=>$postId));
		
		foreach($comments as $comment){
			echo $comment['comments']." ~ ".$comment['username']."<hr />";
		}
		
	}
}
?>