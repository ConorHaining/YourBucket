<?php

//Connection Error Function
function connect_error($reason){
	// Show Error Box
	$show_error = true;
}

// FB Info
$app_id = "387971844591013";
$app_secret = "7fe4f6779ac88eefaec527d0037a4dfe";
$app_scope = "publish_actions";
$app_state = "fingerlickinchicken";
$app_return = "http://localhost/YourBucket/connect";

$user_code;

if(!isset($_GET['code'])){

// Send the user to Facebook for login
header("Location: 
	https://www.facebook.com/dialog/oauth?client_id=".$app_id.
	"&redirect_uri=".$app_return.
	"&scope=".$app_scope.
	"&state=".$app_state."");

}else{
	// Check "State" matches, it's a security thing
	if(isset($_GET['state']) == $app_state){
		$user_code = $_GET['code'];
	}else{
		connect_error("state");
	}
}

// Did the user Deny?
if(isset($_GET['error_reason'])){
	connect_error("user_deny");
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Connecting to Facebook..</title>

	<link rel="stylesheet" type="text/css" href="../css/global.css">
<head>
<body>

</body>
</html>