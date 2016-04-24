<?php
session_start();
include("connection.php"); //Establishing connection with our database

$msg = ""; //Variable for storing our errors.
if(isset($_POST["submit"]))
{
    $title = $_POST["title"];
    $desc = $_POST["desc"];
    $url = "test";
    $name = $_SESSION["username"];
    $userID=$_SESSION["userid"];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $uploadOk = 1;

    //$sql="SELECT userID FROM usersSecure WHERE username='$name'";
   // $result=mysqli_query($db,$sql);
   // $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    // if(mysqli_num_rows($result) == 1)
    if($userID) {
        //$timestamp = time();
        //$target_file = $target_file.$timestamp;
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            //$id = $row['userID'];
            if ($mysqli->connect_errno) {
                echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            }

            //call procedure
            if (! $mysqli->query("CALL sp_insertphotos ('$title','$desc','$target_file','$userID')"))  {
                echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
               // $msg = "Sorry, there was an error uploading your file.";
            }
            else{
                $msg = "Thank You! The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. click <a href='photos.php'>here</a> to go back";


            }


        }
        //echo $name." ".$email." ".$password;


    }
    else{
        $msg = "You need to login first";
    }
}

?>