
<?php
session_start();
?>

<?php
$error = ""; //Variable for storing our errors.
if(isset($_POST["submit"])){
	if (empty($_POST["username"]) || empty($_POST["password"])) {
		$error = "Both fields are required.";
		echo "Both fields are required.";
	}else{

		include("connection.php"); //Establishing connection with our database
		$mysqli = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(!$mysqli) die('Could not connect$: ' . mysqli_error());

		// Define $username and $password
		$username=$_POST['username'];
		$password=$_POST['password'];

		// Prepare OUT parameters
		$mysqli->query("SET @userID=0");

		//call procedure to check user name and password
		$result = $mysqli->query("CALL getAll($username,$password,@userID)");
		$result = $mysqli->query( 'SELECT @userID AS getuserid' );
		$row=$result->fetch_assoc();
		// $row=mysqli_fetch_assoc($result);
		//$userid=$row['getuserid'];
		//if(!$result) die("CALL failed: (" . $mysqli->errno . ") " . $mysqli->error);
		//if(mysqli_num_rows($result) == 1 )
		if($row['getuserid'])
		{
			$userid = $row['getuserid'];//Get user ID
			$_SESSION['username'] = $username; // Initializing Session
			$_SESSION["userid"] = $userid;//user id assigned to session global variable
			echo $userid;

		}else{
			echo "User name or Password is Incorrect";
		}



	}

}


