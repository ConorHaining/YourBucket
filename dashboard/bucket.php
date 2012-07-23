<?php

require '../database.php';

if(isset($_POST['submit'])){

	// Var set
	$name = $_POST['name'];

	if(isset($_POST['friend-1'])){
		$friends = array($_POST['friend-1']);
	}elseif(isset($_POST['friend-2'])){
		array_push($friends, $_POST['friend-2']);
	}elseif(isset($_POST['friend-3'])){
		array_push($friends, $_POST['friend-3']);
	}elseif(isset($_POST['friend-4'])){
		array_push($friends, $_POST['friend-4']);
	}

	$before = $_POST['beforeiam'];

	// Get Users Buckets
	$filename = "data/".$_COOKIE['id'].".json";
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);

	$json = json_decode($contents, true);

	// Construct array to be pushed

	$push = array(
		"id" => $user_db['total']++,
		"title" => $name,
		"with" => $friends,
		"before" => $before,
		"status" => "Not Complete"
	);

	array_push($json, $push);

	$json = json_encode($json);

	// Update the bucket

	$filename = "data/".$_COOKIE['id'].".json";
	$handle = fopen($filename, "w");
	fwrite($handle, $json);
	fclose($handle);

}

?>