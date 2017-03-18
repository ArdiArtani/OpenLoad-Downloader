<?php
require "./vendor/autoload.php";
use JonnyW\PhantomJs\Client;

$video = $_GET['id'];
if($video == ''){
	echo "Where is the media ID?"; //https://openload.co/embed/7zLUwKrlQqCk  (The ID is "7zLUwKrlQqCk" in this case)
	exit();
}else{
	$client = Client::getInstance();

	if(strpos($video, '.') !== False){
		$video = explode('.', $video)[0];
	}

	$request = $client->getMessageFactory()->createRequest("https://openload.co/embed/$video", 'GET');
	$response = $client->getMessageFactory()->createResponse();
	$client->send($request, $response);

	if($response->getStatus() === 200) {
		$openload = $response->getContent();
		if(strpos($openload, 'We are sorry!') !== False){
			echo json_encode(array('error' => '404', 'msg' => 'File not found'));
			exit();
		}
		
  	$openload = explode('<span id="streamurl">', $openload)[1];
  	$file = 'https://openload.co/stream/'.explode('</span>', $openload)[0].'?mime=true';
  	echo $file;
	}else{
		echo json_encode(array('error' => $response->getStatus(), 'msg' => 'Server error'));
		exit();
	}
}
