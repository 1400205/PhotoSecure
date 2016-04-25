<?php
session_start();
?>
<?php
$name = $_SESSION["username"];
$userID=$_SESSION["userid"];
?>
<?php
include("connection.php"); //Establishing connection with our database

$msg = ""; //Variable for storing our errors.

//Function to cleanup user input for xss
function xss_cleaner($input_str) {
    $return_str = str_replace( array('<','>',"'",'"',')','('), array('&lt;','&gt;','&apos;','&#x22;','&#x29;','&#x28;'), $input_str );
    $return_str = str_ireplace( '%3Cscript', '', $return_str );
    return $return_str;
}

if(isset($_POST["submit"]))
{
    $title = $_POST["title"];
    $desc = $_POST["desc"];
    $url = "test";

        //clean user input
    $title=mysqli_real_escape_string($db,$title);
    $desc=mysqli_real_escape_string($db,$desc);

    //clean user inputs from xss
    $desc= xss_cleaner($desc);
    $title= xss_cleaner($title);

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

           //connect to db
            $mysqli = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
            //if(!$mysqli) die('Could not connect$: ' . mysqli_error());

            //test connection
            if ($mysqli->connect_errno) {
                echo "Connection Fail:Check network connection";//: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            }


            //create procedure

            //if (!$mysqli->query("DROP PROCEDURE IF EXISTS insertPhoto") ||
               // !$mysqli->query('CREATE PROCEDURE insertPhoto(IN strtitle varchar(255),IN strDesc varchar(255),
               // IN datpostDate(DateTime),IN strurl text(500), IN intuserID int(11))
			   // BEGIN
			    //    INSERT INTO photosecure
               // ( title,description,postDate,url,userID )
               // VALUES (strtitle, strDesc,datpostDate,strurl, intuserID);END;')) {
               // echo "Stored procedure creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
           // }

            //call procedure
            if (! $mysqli->query("CALL sp_insertphotos('$title','$desc','$target_file','$userID')"))  {
                //echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
               // $msg = "Sorry, there was an error uploading your file.";
            }else{

                $msg = "Thank You! The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded. click <a href='photos.php'>here</a> to go back";

            }



        }
        //echo $name." ".$email." ".$password;


    }
    else{
        $msg = "You need to login first";
    }
}

?>