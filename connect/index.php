<?php
require '../database.php';

//Connection Error Function
function connect_error($reason){
	// Show Error Box
	global $show_error, $error_message;
	$show_error = true;

	//Set the message
	switch ($reason) {
		case 'state':
			$error_message = "We send a unique code to Facebook as you connect, this did not return back the same. You may have accidentally changed the URL, or you could be a bad robot. Go back to the <a href=\"../\">Homepage</a> and log in again. Bad robot need not bother";
			break;
		case 'user_deny':
			$error_message = "It appear you didn't want to sign up using Facebook, but it's the only way to login. Was it a mistake? That's cool, just go back to the <a href=\"../\">Homepage</a> and log in again.";
			break;
	}
}

// FB Info
$app_id = "387971844591013";
$app_secret = "7fe4f6779ac88eefaec527d0037a4dfe";
$app_scope = "publish_actions,email";
$app_state = md5("fingerlickinchicken");
$app_return = "http://localhost/YourBucket/connect";

$user_code;

if(isset($_GET['code'])){

	// Check "State" matches, it's a security thing
	if($_GET['state'] == $app_state){
		$user_code = $_GET['code'];

		//Get access token
		$token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($app_return)
       . "&client_secret=" . $app_secret . "&code=" . $user_code;

		$token = file_get_contents($token_url);
	    $params = null;
	    parse_str($token, $params);

	    $graph_url = "https://graph.facebook.com/me?access_token=" 
	      . $params['access_token'];

	    $user = json_decode(file_get_contents($graph_url));

	    //Add into Database if not already there
	    $q = mysql_query("SELECT * FROM user WHERE fb_id=".$user->id."");

		if(mysql_num_rows($q) == 0){
			mysql_query("INSERT INTO user (fb_id, email) VALUES ('".$user->id."', '".$user->email."')");
		}
		
		// Update Token and fire user to the dashboard
		mysql_query("UPDATE user SET token='".$token."' WHERE fb_id='".$user->id."'");
		$_SESSION['fb_id'] = $user->id;
		header("Location: ../dashboard");

	}else{
		connect_error("state");
	}

}elseif(isset($_GET['error_reason']) == "user_denied"){
	connect_error("user_deny");
}else{

	// Send the user to Facebook for login
	header("Location: 
	https://www.facebook.com/dialog/oauth?client_id=".$app_id.
	"&redirect_uri=".$app_return.
	"&scope=".$app_scope.
	"&state=".$app_state."");
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Connecting to Facebook..</title>

	<link rel="stylesheet" type="text/css" href="../css/normalize.css">
	<link rel="stylesheet" type="text/css" href="../css/global.css">
<head>
<body>
	<div id="wrapper">
		<header>
			<a href="../" title="Return to the homepage" id="logo">
				<img src="/images/logo.png" alt="YourBucket Logo" />
			</a>
			<?php if(isset($user)) : ?>
				<div class="user">
					<img src="http://graph.facebook.com/<?php echo $user->id; ?>/picture">
					<?php echo $user->name; ?>
				</div>
			<?php endif; ?>
		</header>
		<?php if(isset($show_error) == true) : ?>
		<div class="message">
			<h3>Glitch in the Matrix</h3>
			<?php echo $error_message; ?>
		</div>
		<?php else: ?>
		<div class="message">
			Loading...
			<!-- Maybe have a spinner? -->
		</div>
		<?php endif; ?>
	</div>
</body>
</html>