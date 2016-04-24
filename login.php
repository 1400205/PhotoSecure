
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

		//test connection
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}

		//create procedure

		if (!$mysqli->query("DROP PROCEDURE IF EXISTS getUserID") ||
			!$mysqli->query('CREATE PROCEDURE getUserID(IN loc_username varchar(255),
			 IN loc_password varchar(255), OUT loc_userID int)
			BEGIN
 			SELECT `userID` INTO loc_userID FROM userssecure WHERE username = loc_username
     	AND password = loc_password;END;')) {
			echo "Stored procedure creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}

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


