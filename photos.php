<?php
session_start();
//include ("secureSessionID.php");//verify user session
//include ("inactiveTimeOut.php");//check user idle time
?>
<?php
	include("check.php");
	include("userphotos.php");
$login_user= $_SESSION["username"];

?>
<?php
if(!isset($_SESSION["timeout"])){
$_SESSION['timeout'] = time();
}
$st = $_SESSION['timeout'] + 60; //session time is 1 minute

if(time() < $st){
	echo 'Session will last 1 minute';
	//echo $_SESSION['timeout'] = time();
	session_destroy();
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Home</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>

<h4>Welcome <?php echo $login_user;?> <a href="photos.php" style="font-size:18px">Photos</a>||<a href="searchphotos.php" style="font-size:18px">Search</a>||<a href="logout.php" style="font-size:18px">Logout</a></h4>

<div id="photolist">
	<?php echo $resultText;?>
</div>
<a href='addphotoform.php'> Add New Photo </a>;

</body>
</html>