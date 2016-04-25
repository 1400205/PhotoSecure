
<?php
session_start();
?>
<?php
	include("connection.php"); //Establishing connection with our database

	//Function to cleanup user input for xss
	function xss_cleaner($input_str)
	{
	$return_str = str_replace( array('<','>',"'",'"',')','('), array('&lt;','&gt;','&apos;','&#x22;','&#x29;','&#x28;'), $input_str );
	$return_str = str_ireplace( '%3Cscript', '', $return_str );
	return $return_str;
}
	//session IP binding
	//$IP=getenv("REOMOTE_ADDR");


	$error = ""; //Variable for storing our errors.
	if(isset($_POST["submit"]))
	{
		if(empty($_POST["username"]) || empty($_POST["password"]))
		{
			$error = "Both fields are required.";
		}else {
			// Define $username and $password
			$username = $_POST['username'];
			$password = $_POST['password'];

			//clean user input
			$username=mysqli_real_escape_string($db,$username);

			//enrypt password
			$password=md5($password);

			//clean user input from xss
			$username=xss_cleaner($username );

			//Check username and password from database
			//$sql="SELECT userID FROM userssecure WHERE username='$username' and password='$password'";
			//$result=mysqli_query($db,$sql);
			//$row=mysqli_fetch_array($result,MYSQLI_ASSOC) ;
			//$userid=$row['userID'];//Get user ID

			//If username and password exist in our database then create a session.
			//Otherwise echo error.

			//instance of connection to dbase
			$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			//if(!$mysqli) die('Could not connect$: ' . mysqli_error());

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
     		AND password = loc_password;END;')
			) {
				//echo "Stored procedure creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}

			// Define $username and $password
			//$username=$_POST['username'];
			//$password=$_POST['password'];

			// Prepare OUT parameters
			$mysqli->query("SET @userID=0");

			if (!$mysqli->query("CALL getUserID('$username','$password',@userID)")) {
				//echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}


			$res = $mysqli->query("SELECT @userID as userID");
		}
			$row = $res->fetch_assoc();
			$userid=$row['userID'];//Get user ID



			if ($userid < 1)
			{
				 echo  "Incorrect username or password.";

			}else
			{
				$_SESSION['username'] = $username; // Initializing Session
				$_SESSION["userid"] = $userid;//user id assigned to session global variable
				header("location: photos.php"); // Redirecting To Other Page
			}


	}

?>