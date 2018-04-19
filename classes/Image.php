<?php
class Image{
	
	public static function uploadImage($formname, $query, $params){
		$image = base64_encode(file_get_contents($_FILES[$formname]['tmp_name']));
	
		$options = array('http'=>array(
			'method'=>"POST",
			'header'=>"Authorization: Bearer e87355a94928e4ffa47a2a74b924d6cb4c72b40d\n".
			"Content-Type: application/x-wwwform-urlencoded",
			'content'=>$image
		));

		$context = stream_context_create($options);
	
		$imgurURL = "https://api.imgur.com/3/image";
		
		if($_FILES[$formname]['size'] > 10240000){
			die('Image too big, it must be 10MB or less!');
		}
	
		$response = file_get_contents($imgurURL, false, $context);
		$response = json_decode($response);
	
		/*echo '<pre>';
		print_r($response);
		echo "</pre>";*/
		
		$preparams = array($formname=>$response->data->link);
		
		$params = $preparams +$params;
		
		DB::query($query, $params);

	}
}
?>