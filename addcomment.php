<?php
session_start();
include("connection.php"); //Establishing connection with our database
//include ("secureSessionID.php");//verify user session
//include ("inactiveTimeOut.php");//check user idle time
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

<?php

//get the session variables

$name = $_SESSION["username"];
$userID=$_SESSION["userid"];

?>
<?php
$msg = ""; //Variable for storing our errors.

//function xss_cleaner_high
function xssafe($data,$encoding='UTF-8'){
    return htmlspecialchars($data,ENT_QUOTES|ENT_HTML401|ENT_HTML401);
}


//Function to cleanup user input for xss
function xss_cleaner($input_str) {
    $return_str = str_replace( array('<','>',"'",'"',')','('), array('&lt;','&gt;','&apos;','&#x22;','&#x29;','&#x28;'), $input_str );
    $return_str = str_ireplace( '%3Cscript', '', $return_str );
    return $return_str;
}

if(isset($_POST["submit"]))
{

    $desc = $_POST["desc"];
    $photoID = $_POST["photoID"];
    $name = $_SESSION["username"];

    //clean user input
    $dec=mysqli_real_escape_string($db,$desc);
   $photoID=mysqli_real_escape_string($db,$photoID);
   $name=mysqli_real_escape_string($db,$name);

    //clean inputs for xss
    $desc=xss_cleaner($desc);
    $name=xss_cleaner($name);
    $photoID=xss_cleaner($photoID);

   // $sql="SELECT userID FROM usersSecure WHERE username='$name'";
   // $result=mysqli_query($db,$sql);
    //$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    if($userID >0) {

        //connect to db
        $mysqli = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
        //if(!$mysqli) die('Could not connect$: ' . mysqli_error());

        //test connection
        if ($mysqli->connect_errno) {
            echo "Connetion Failed:check network connection";// to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }



        //call procedure
        if (! $mysqli->query("CALL sp_insertComments('$desc','$photoID','$userID')"))  {
            //echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
            // $msg = "Sorry, there was an error uploading your file.";
        }else{

            $msg = "Thank You! comment added. click <a href='photo.php?id=".$photoID."'>here</a> to go back";
        }
    }
    else{
        $msg = "You need to login first";
    }
}

?>