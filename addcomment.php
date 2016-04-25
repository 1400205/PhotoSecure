<?php
session_start();
include("connection.php"); //Establishing connection with our database
?>


<?php

//get the session variables

$name = $_SESSION["username"];
$userID=$_SESSION["userid"];
?>
<?php
$msg = ""; //Variable for storing our errors.
if(isset($_POST["submit"]))
{

    $desc = $_POST["desc"];
    $photoID = $_POST["photoID"];
    $name = $_SESSION["username"];

    //clean user input
    $dec=mysqli_real_escape_string($db,$desc);
    $photoID=mysqli_real_escape_string($db,$photoID);
    $name=mysqli_real_escape_string($db,$name);

    //function for xssafe
   function xssafe($data,$encoding='UTF-8'){
    return htmlspecialchars($data,ENT_QUOTES|ENT_HTML401|ENT_HTML5,$encoding);}

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