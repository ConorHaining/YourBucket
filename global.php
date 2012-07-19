<?
$dbHost = "localhost";        //Location Of Database usually its localhost
$dbUser = "root";            //Database User Name
$dbPass = "root";            //Database Password
$dbDatabase = "yourbcket";       //Database Name
 
$db = mysql_connect("$dbHost", "$dbUser", "$dbPass") or die ("Error connecting to database.");
mysql_select_db("$dbDatabase", $db) or die ("Couldn't select the database.");

echo "Connecting..";
?>