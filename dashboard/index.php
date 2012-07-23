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

// Easier Var & Cookie set (For bucket.js)
$id = $_SESSION['fb_id'];
setcookie("id", $id);

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
	<link rel="stylesheet" type="css/text" href="../css/jquery-ui-1.8.21.custom.css">
</head>
<body>
	<div class="overlay"></div>
	<div class="add-bucket-popup">
		<h3>Add New Bucket</h3>

		<form method="post" action="bucket.php">
			<div class="group">
				<label for="title">Bucket Name: </label>
				<input type="text" name="name" placeholder="Skydiving">
			</div>
			<div class="fb_friends group">
				<label for="title">Who with?: </label>
				<select name="friend-1" id="fb_friends">
					<?php foreach($friends['data'] as $f) : ?>
						<option value="<?php echo $f['id']; ?>"><?php echo $f['name']; ?></option>
					<?php endforeach; ?>
				</select>
				<a href="#" class="add-friend" title="More friends">+</a>
			</div>
			<div class="group">
				<label for="title">Before I'm: </label>
				<input type="number" name="beforeiam" min="13" step="1">
			</div>
			<div class="group">
				<input type="submit" name="submit" value="Add Bucket"/>
			</div>
		</form>
	</div>
	<div id="wrapper">
		<header class="small">
			<a href="" id="logo">
				<img src="../img/logo-small.png" alt="YourBucket Logo" />
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
					<td>Before I am</td>
					<td>How's it going?<td>
				</thead>
				<tbody>
					<?php while ($i <= $user_db['total']) : ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $json[$i]['title']; ?></td>
							<td><?php findfriend($json[$i]['with'], $user_db['total']); ?></td>
							<td><?php echo $json[$i]['before']; ?></td>
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
	<script type="text/javascript" src="../js/modernizr.js"></script>
	<script type="text/javascript" src="../js/bucket.js"></script>
</body>
</html>