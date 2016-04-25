<?php
/**
 * Created by PhpStorm.
 * User: prosper
 * Date: 25/04/2016
 * Time: 15:26
 */

session_start();

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

// If the user is already logged
if (isset($_SESSION['uid'])) {
    // If the IP or the navigator doesn't match with the one stored in the session
    // there's probably a session hijacking going on
    //session IP binding
    //$IP=$_SERVER['REMOTE_ADDR'];

    if ($_SESSION['ip'] !==$_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent_id'] !== $_SERVER['HTTP_USER_AGENT']) {
        // Then it destroys the session
        session_unset();
        session_destroy();
        header("Location: index.php");
        // Creates a new one
        session_regenerate_id(true); // Prevent's session fixation
        //session_id(sha1(uniqid(microtime())); // Sets a random ID for the session
    }
} else {
    session_regenerate_id(true); // Prevent's session fixation
    //session_id(sha1(uniqid(microtime())); // Sets a random ID for the session
    // Set the default values for the session
    setSessionDefaults();
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR']; // Saves the user's IP
    $_SESSION['user_agent_id'] = $_SERVER['HTTP_USER_AGENT']; // Saves the user's navigator
}
?>