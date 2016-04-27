<?php
session_start();
//include ("secureSessionID.php");//verify user session
//include ("inactiveTimeOut.php");//check user idle time
?>
<?php
$name = $_SESSION["username"];
$userID=$_SESSION["userid"];
$ip=$_SESSION["ip"];
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
    //clean input title
    $title = stripslashes( $title );
    $title=mysqli_real_escape_string($db,$title);
    $title = htmlspecialchars( $title );
    $title=xssafe($title);

    //clean input description
    $desc = stripslashes( $desc );
    $desc=mysqli_real_escape_string($db,$desc);
    $desc = htmlspecialchars( $desc );
    $desc=xssafe($desc);

    //check for file upload error
    if($_FILES['fileToUpload']['error'] == 0) {

        // Where are we going to be writing to?
        $target_dir = "uploads/";
        $target_file = basename($_FILES['fileToUpload']['name']);

        // File information
        $uploaded_name = $_FILES['fileToUpload']['name'];
        $uploaded_ext = substr($uploaded_name, strrpos($uploaded_name, '.') + 1);
        $uploaded_size = $_FILES['fileToUpload']['size'];
        $uploaded_tmp = $_FILES['fileToUpload']['tmp_name'];
        $uploadOk = 1;
    }

    if($userID >0) {

        //restrict file type and size
        if( ( strtolower( $uploaded_ext ) == "jpg" || strtolower( $uploaded_ext ) == "jpeg" || strtolower( $uploaded_ext ) == "png" ) &&
            ( $uploaded_size < 100000 ) &&
            getimagesize( $uploaded_tmp ) ) {

            // Can we move the file to the upload folder?
            if (move_uploaded_file($uploaded_tmp, $target_file)) {
                //connect to db
                $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
                //if(!$mysqli) die('Could not connect$: ' . mysqli_error());

                //test connection
                if ($mysqli->connect_errno) {
                    echo "Connection Fail:Check network connection";//: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
                }

                //call procedure
                if (!$mysqli->query("CALL sp_insertphotos('$title','$desc','$target_file','$userID')")) {

                } else {

                    $msg = "Thank You! The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded. click <a href='photos.php'>here</a> to go back";

                }
            }
            else{
                $msg = "Your image was not uploaded";
            }
        }else{
            $msg = "Your image was not uploaded. We can only accept JPEG or PNG images.";
        }

    }
    else{
        $msg = "You need to login first";
    }
}

?>