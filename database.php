<?php
session_start();

$dbHost = "localhost";        //Location Of Database usually its localhost
$dbUser = "root";            //Database User Name
$dbPass = "root";            //Database Password
$dbDatabase = "yourbucket";       //Database Name
 
$db = mysql_connect("$dbHost", "$dbUser", "$dbPass") or die ("Error connecting to database.");
mysql_select_db("$dbDatabase", $db) or die ("Couldn't select the database.");

// Get users info from DB
$user_db = mysql_query("SELECT * FROM user WHERE fb_id='".$_SESSION['fb_id']."'");
$user_db = mysql_fetch_assoc($user_db);

?>