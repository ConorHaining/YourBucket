<?php

require '../database.php';

//Check if user logged in
if(!isset($_SESSION['fb_id'])){
	header("Location: ../");
	exit();
}

function findfriend($friends, $t){

	// Last Item
	$last = end($friends);
	// Second Last
	$sLast = prev($friends);

	reset($friends);

	foreach ($friends as $f) {
		$info = file_get_contents("http://graph.facebook.com/".$f);
		$info = json_decode($info, true);

		if($f === $last){
			echo $info['name'];
		}elseif($f === $sLast){
			echo $info['name'] . " and ";
		}else{
			echo $info['name'] . ", ";
		}

	}
}

// Easier Var
$id = $_SESSION['fb_id'];

// Get users info from DB
$user_db = mysql_query("SELECT * FROM user WHERE fb_id='".$id."'");
$user_db = mysql_fetch_assoc($user_db);

// Get Users Buckets
$filename = "data/".$id.".json";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);

$json = json_decode($contents, true);

$i = 1;
// Get user's friends
$friends = file_get_contents("https://graph.facebook.com/".$id."/friends?".$user_db['token']);
$friends = json_decode($friends, true);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard | YourBucket</title>

	<link rel="stylesheet" type="css/text" href="../css/normalize.css">
	<link rel="stylesheet" type="css/text" href="../css/global.css">
	<link rel="stylesheet" type="css/text" href="../css/dashboard.css">
</head>
<body>
	<div class="overlay"></div>
	<div class="add-bucket-popup">
		<h3>Add New Bucket</h3>

		<form method="post" action="">
			<label for="title">Bucket Name: </label>
			<input type="text" placeholder="Skydiving">
			<label for="title">Who with?: </label>
			<input class="datalist-input" type="text" list="fb_friends">
			<datalist id="fb_friends">
				<?php foreach($friends['data'] as $f) : ?>
					<option><?php echo $f['name']; ?></option>
				<?php endforeach; ?>
			</datalist>
			<label for="title">Before I'm: </label>
			<input type="number" min="13" step="1">
		</form>
	</div>
	<div id="wrapper">
		<header class="small">
			<a href="" id="logo">
				<img src="/images/logo.png" alt="YourBucket Logo" />
			</a>
		</header>
		<aside class="sidebar">

		</aside>
		<section class="dashboard">
			<h3>Your Bucket</h3>
			<a class="add-bucket" href="#">+ Add Bucket</a>

			<table>
				<thead>
					<td>#</td>
					<td>Bucket</td>
					<td>With Who?</td>
					<td>How's it going?<td>
				</thead>
				<tbody>
					<?php while ($i <= $user_db['total']) : ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $json[$i]['title']; ?></td>
							<td><?php findfriend($json[$i]['with'], $user_db['total']); ?></td>
							<td><?php echo $json[$i]['status']; ?></td>
						</tr>
					<?php $i++; endwhile; ?>
				</tbody>
			</table>
		</section>
		<footer>

		</footer>
	</div>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="../js/modernizr.custom.95508.js"></script>
	<script type="text/javascript" src="../js/bucket.js"></script>
</body>
</html>