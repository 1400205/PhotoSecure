
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

		if (!$mysqli->query("SET @userID = 1") || !$mysqli->query("CALL getAll($username,$password,@userID)")) {
			echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}

		if (!($res = $mysqli->query("SELECT @userID as p_out"))) {
			echo "Fetch failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}else {

			$row = $res->fetch_assoc();
			echo $row['_p_out'];
		}





	}

}


