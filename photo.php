<?php
session_start();
$login_user= $_SESSION["username"];
$login_userID= $_SESSION["userid"];
//include ("secureSessionID.php");//verify user session
//include ("inactiveTimeOut.php");//check user idle time
?>
<?php
include("check.php");
include("connection.php");
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>
<h4>Welcome <?php echo $login_user;?>  <a href="photos.php" style="font-size:18px">Photos</a>||<a href="searchphotos.php" style="font-size:18px">Search</a>||<a href="logout.php" style="font-size:18px">Logout</a></h4>
<div id="photo">
    <?php
    if(isset($_GET['id'])){

        //clean input user name
        // $photoID = stripslashes( $photoID );
        //$photoID=mysqli_real_escape_string($db,$photoID);
        //$photoID = htmlspecialchars( $photoID );

        //instance of connection to dbase
        $sqlidb = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if ($sqlidb->connect_errno){
            echo"connection Failed";
        }
        //sql statement

        $photosql='SELECT * FROM photosSecure WHERE photoID=?';

        //inititalilised the statement
        $stm=$sqlidb->init();

        //prepare statement
        if(!($stm->prepare($photosql))){
            echo "prepared statement failed";
        }
        else{
            //bind parameter
            $stm->bind_param('i', $_GET['id']);
            $stm->execute();
            $result=$stm->get_result();
            $row=$result->fetch_assoc();

            // $photoRow = mysqli_fetch_assoc($photoresult);
            echo "<p>".$row['title']."</p>";
            echo "<h3>".$row['postDate']."</h3>";
            echo "<img src='".$row['url']."'/>";
            echo " <p>".$row['description']."</p>";

                 $photoID = $_GET['id'];

            $commentSql="SELECT * FROM commentsSecure WHERE photoID='$photoID'";
            $commentresult=mysqli_query($db,$commentSql) or die ("application cannot connect;check network");//die(mysqli_error($db));
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
        // $photoresult=$photoSql->fetch(PDO::FETCH_ASSOC);

        //$photoID = $_GET['id'];

        //$photoSql=$mysqli->prepare("SELECT * FROM photosSecure WHERE photoID=:pid");
        //$photoSql->bindParam(':pid', $photoID);
        // $photoSql->execute();

        // $result = $photoSql -> fetch();

        //print_r($result);

        //prevent system errors from been seen by user
        // $photoresult=mysqli_query($db,$photoSql) or die("application cannot connect;check network");

        // if($photoresult){
        // $photoRow = mysqli_fetch_assoc($photoresult);
        // echo "<p>".$photoresult['title']."</p>";
        // echo "<h3>".$photoresult['postDate']."</h3>";
        //  echo "<img src='".$photoresult['url']."'/>";
        //  echo " <p>".$photoresult['description']."</p>";


        // $commentSql="SELECT * FROM commentsSecure WHERE photoID='$photoID'";
        // $commentresult=mysqli_query($db,$commentSql) or die ("application cannot connect;check network");//die(mysqli_error($db));
        // if(mysqli_num_rows($commentresult)>1) {

        // echo "<h2> Comments </h2>";
        //  while($commentRow = mysqli_fetch_assoc($commentresult)){
        //      echo "<div class = 'comments'>";
        //      echo "<h3>".$commentRow['postDate']."</h3>";
        //       echo "<p>".$commentRow['description']."</p>";
        //        echo "</div>";
        //     }

        // }
        // echo "<a href='addcommentform.php?id=".$photoID."'> Add Comment</a><br>";

        // if($adminuser){
        //     echo "<div class='error'><a href='removephoto.php?id=".$photoID."'> Delete Photo</a></div>";
        //  }

        // }
        // else{
        //    echo "<h1>No Photos Found</h1>";
        // }

    }
    else{

        echo "<h1>No User Selected</h1>";
    }

    ?>
</div>

</body>
</html>
