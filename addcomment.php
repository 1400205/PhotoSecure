<?php
session_start();
include("connection.php"); //Establishing connection with our database
?>


<?php
//connect to db
$mysqli = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
if(!$mysqli) die('Could not connect$: ' . mysqli_error());

//get the session variables

$name = $_SESSION["username"];
$userID=$_SESSION["userid"];
?>
<?php
$msg = ""; //Variable for storing our errors.

if(isset($_POST["submit"]))
{
    // Check Anti-CSRF token
    checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );


    $desc = $_POST["desc"];
    $photoID = $_POST["photoID"];
    $name = $_SESSION["username"];

    //clean input description
    $desc = stripslashes( $desc );
    $desc=mysqli_real_escape_string($db,$desc);
    $desc = htmlspecialchars( $desc );
    $desc=xssafe($desc);

    //clean input name
    $name = stripslashes( $name );
    $name=mysqli_real_escape_string($db,$name);
    $name = htmlspecialchars($name);
    $name=xssafe($name);

    //clean input photo ID
    $photoID = stripslashes( $photoID );
    $photoID=mysqli_real_escape_string($db,$photoID);
    $photoID = htmlspecialchars($photoID);
    $photoID=xssafe($photoID);

    if($userID >0) {
        //test connection
        if ($mysqli->connect_errno) {
            echo "Connetion Failed:check network connection";// to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        //call procedure
        if (! $mysqli->query("CALL sp_insertComments('$desc','$photoID','$userID')"))  {
            echo "Procedure Call Failed:";

        }else{

            $msg = "Thank You! comment added. click <a href='photo.php?id=".$photoID."'>here</a> to go back";
        }
    }
    else{
        $msg = "You need to login first";
    }
}

?>