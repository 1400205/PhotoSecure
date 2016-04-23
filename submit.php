<?php
$msg = "";
//connections
include("connection.php"); //Establishing connection with our database
$mysqli = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
if(!$mysqli) die('Could not connect$: ' . mysqli_error());

if(isset($_POST["submit"]))
{
    $name = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];



    $sql="SELECT email FROM users WHERE email='$email'";
    $result=mysqli_query($db,$sql);
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    if(mysqli_num_rows($result) == 1)
    {
        $msg = "Sorry...This email already exists...";
    }
    else
    {
        //call procedure
        $result = $mysqli->query("CALL sp_insertUserDetails($email,$username,$password)");
        $result = $mysqli->query( 'SELECT @userID' );
        //if(!$result) die("CALL failed: (" . $mysqli->errno . ") " . $mysqli->error);
        //echo $name." ".$email." ".$password;
       // $query = mysqli_query($db, "INSERT INTO usersSecure (username, email, password) VALUES ('$name', '$email', '$password')")or die(mysqli_error($db));
        if($result)
        {
            $msg = "Thank You! you are now registered. click <a href='index.php'>here</a> to login";
        }

    }
}
?>