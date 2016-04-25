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
//session time sub
if($_SESSION ['timeout']+ 60 < time()){

	//session timed out
	session.session_destroy();
	Header("Location:logout.php");
}else{
	//reset session time
	$_SESSION['timeout']=time();
}
?>

<?php

// If the user is already logged
if (isset($_SESSION['uid'])) {
	// If the IP or the navigator doesn't match with the one stored in the session
	// there's probably a session hijacking going on
	//session IP binding
	//$IP=$_SERVER['REMOTE_ADDR'];

	if ( $_SESSION['user_agent_id'] !== $_SERVER['HTTP_USER_AGENT']) {
		// Then it destroys the session
		session_unset();
		session_destroy();
		//header("Location: logout.php");
		// Creates a new one
		session_regenerate_id(true); // Prevent's session fixation
		//session_id(sha1(uniqid(microtime())); // Sets a random ID for the session
	}
} else {
	session_regenerate_id(true); // Prevent's session fixation
	//session_id(sha1(uniqid(microtime())); // Sets a random ID for the session
	// Set the default values for the session
	//setSessionDefaults();
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR']; // Saves the user's IP
	$_SESSION['user_agent_id'] = $_SERVER['HTTP_USER_AGENT']; // Saves the user's navigator
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