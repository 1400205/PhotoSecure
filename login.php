
<?php
session_start();
?>
<?php
	include("connection.php"); //Establishing connection with our database
$mysqli = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
if(!$mysqli) die('Could not connect$: ' . mysqli_error());

$error = ""; //Variable for storing our errors.
	if(isset($_POST["submit"]))
	{
		if(empty($_POST["username"]) || empty($_POST["password"]))
		{
			$error = "Both fields are required.";
		}

		else
		{
			// Define $username and $password
			$username=$_POST['username'];
			$password=$_POST['password'];

			// Prepare IN parameters
			//$mysqli->query("SET @username  = " . "'" . $mysqli->real_escape_string($username) . "'");
			//$mysqli->query("SET @password   = " . "'" . $mysqli->real_escape_string(password) . "'");
			$mysqli->query("SET @userID = 0");

			
			//Check username and password from database
			//$sql="SELECT userID FROM userssecure WHERE username='$username' and password='$password'";

			//call procedure
			//$sql="CALL getAll($username,$password)";
			$result = $mysqli->query("CALL getAll($username,$password,@userID)");
			$result = $mysqli->query( 'SELECT @userID' );
			//if(!$result) die("CALL failed: (" . $mysqli->errno . ") " . $mysqli->error);
			if($result->num_rows ==1){
				$row=mysqli_fetch_array($result,MYSQLI_ASSOC) ;
				$userid=$row['userID'];//Get user ID
				$_SESSION['username'] = $username; // Initializing Session
				$_SESSION["userid"] = $userid;//user id assigned to session global variable
				header("location: photos.php"); // Redirecting To Other Page
			}
			else
			{
				$error = "Incorrect username or password.";
			}

		}
	}

?>