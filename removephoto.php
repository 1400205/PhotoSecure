<?php
session_start();
include("connection.php"); //Establishing connection with our database

//include ("secureSessionID.php");//verify user session
//include ("inactiveTimeOut.php");//check user idle time

//Function to cleanup user input for xss
function xss_cleaner($input_str) {
    $return_str = str_replace( array('<','>',"'",'"',')','('), array('&lt;','&gt;','&apos;','&#x22;','&#x29;','&#x28;'), $input_str );
    $return_str = str_ireplace( '%3Cscript', '', $return_str );
    return $return_str;
}

if(isset($_GET['id']))
{
    $photoID = $_GET['id'];

    //filter input from get
    $photoID=mysqli_real_escape_string($db,$photoID);

    //filter $_Get from xss attack
    $photoID=xss_cleaner($photoID);

    $remsql = "DELETE FROM photosSecure WHERE photoID='$photoID'";
    $query = mysqli_query($db, $remsql) or die(mysqli_error($db));
    if ($query) {
        header("Location: photos.php");
    }
    else {
        echo "Sorry, there was an error deleting the file.";
    }
    //echo $name." ".$email." ".$password

}

?>