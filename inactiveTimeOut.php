<?php
/**
 * Created by PhpStorm.
 * User: prosper
 * Date: 25/04/2016
 * Time: 19:34
 */
//This is to log out idle users
if($_SESSION ['timeout']+ 60 < time()){

    //session timed out
    session.session_destroy();
    Header("Location:login.php");
}else{
    //reset session time
    $_SESSION['timeout']=time();
}
