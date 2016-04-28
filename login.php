<?php
session_start();
?>
<?php
include("connection.php"); //Establishing connection with our database

function get_client_ip() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

//Function to cleanup user input for xss
function xss_cleaner($input_str) {
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

		//clean input user name
		$username = stripslashes( $username );
		$username=mysqli_real_escape_string($db,$username);
		$username = htmlspecialchars( $username );
		$username=xssafe($username);

		//enrypt password
		$password=md5($password);

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
			echo "Stored procedure creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}

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
		//get a variable to count number of logins
		$count=0;
		$count=$count+1;
		echo  $count;

	}else
	{
			//get session data
		$_SESSION['username'] = $username; // Initializing Session
		$_SESSION["userid"] = $userid;//user id assigned to session global variable
		$_SESSION["ip"] = $_SERVER['REMOTE_ADDR'];
		$_SESSION ["timeout"]=time();

		header("location: photos.php"); // Redirecting To Other Page
	}


}

?>