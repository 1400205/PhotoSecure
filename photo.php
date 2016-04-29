<?php
session_start()
?>
<?php
include("check.php");
include("connection.php");
$ip=$_SESSION["ip"];
$timeout=$_SESSION ["timeout"];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>
<h4>Welcome <?php echo $login_user;?> <a href="photos.php" style="font-size:18px">Photos</a>||<a href="searchphotos.php" style="font-size:18px">Search</a>||<a href="logout.php" style="font-size:18px">Logout</a></h4>
<div id="photo">
    <?php
    if(isset($_GET['id']))
    {

        $photid=$_GET['id'];
        if (!($ip==$_SERVER['REMOTE_ADDR'])){
            header("location: logout.php"); // Redirecting To Other Page
        }

        if($_SESSION ["timeout"]+60 < time()){

            //session timed out
            header("location: logout.php"); // Redirecting To Other Page
        }else{
            //reset session time
            $_SESSION['timeout']=time();
        }






        $photoID = $_GET['id'];

        //clean input user name
        //$photoID = stripslashes( $photoID );
       // $photoID=mysqli_real_escape_string($db,$photoID);
      //  $photoID = htmlspecialchars( $photoID );
        if( $photoID ){
            echo $photoID;
        }


        //instance of connection to dbase
        $sqlidb = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if ($sqlidb->connect_errno){
            echo"connection Failed";
        }
        //prepared statement statement

       if(($stmt=$sqlidb->prepare("SELECT title,postDate,url,description FROM photosSecure WHERE photoID=?"))) {
          // echo "prepared failed:" . $sqlidb_ > ernno . ")" . $sqlidb->error;


        //bind parameter
        $stmt->bind_param('i',$_GET['id']);
        $stmt->execute();
      // $result = $stmt->get_result();
           $stmt->bind_result($title, $pdate,$url,$desc);

        while ($stmt->fetch()){
            echo "<h1>".$title."</h1>";
           echo "<h3>".$pdate."</h3>";
            echo "<img src='".$url."'/>";
            echo " <p>".$desc."</p>";

            // echo "<h1>".$row['title']."</h1>";
            // echo "<h3>".$row['postDate']."</h3>";
            // echo "<img src='".$row['url']."'/>";
            //echo " <p>".$row['description']."</p>";

        }$result->close();

       }$sqlidb->close();




            $commentSql="SELECT * FROM commentsSecure WHERE photoID='$photoID'";
            $commentresult=mysqli_query($db,$commentSql) or die(mysqli_error($db));
            if(mysqli_num_rows($commentresult)>1) {

                echo "<h2> Comments </h2>";
                while($commentRow = mysqli_fetch_assoc($commentresult)){
                    echo "<div class = 'comments'>";
                    echo "<h3>".$commentRow['postDate']."</h3>";
                    echo "<p>".$commentRow['description']."</p>";
                    echo "</div>";
                }

            }
            echo "<a href='addcommentform.php?id=".$photoID."'> Add Comment</a><br>";

            if($adminuser){
                echo "<div class='error'><a href='removephoto.php?id=".$photoID."'> Delete Photo</a></div>";
            }



    }
    else{

        echo "<h1>No User Selected</h1>";
    }

    ?>
</div>

</body>
</html>
