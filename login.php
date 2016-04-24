
<?php
session_start();
?>

<?php
$error = ""; //Variable for storing our errors.
if(isset($_POST["submit"])){
	if (empty($_POST["username"]) || empty($_POST["password"])) {
		$error = "Both fields are required.";
		echo "Both fields are required.";
	}
}
	include("connection.php"); //Establishing connection with our database
$mysqli = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
if(!$mysqli) die('Could not connect$: ' . mysqli_error());


