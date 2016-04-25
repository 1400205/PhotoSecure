<?php
/**
 * Created by PhpStorm.
 * User: prosper
 * Date: 25/04/2016
 * Time: 15:26
 */

session_start();

// If the user is already logged
if (isset($_SESSION['uid'])) {
    // If the IP or the navigator doesn't match with the one stored in the session
    // there's probably a session hijacking going on
    //session IP binding
    $IP=getenv("REOMOTE_ADDR");

    if ($_SESSION['ip'] !== getIp() || $_SESSION['user_agent_id'] !== getUserAgentId()) {
        // Then it destroys the session
        session_unset();
        session_destroy();

        // Creates a new one
        session_regenerate_id(true); // Prevent's session fixation
        //session_id(sha1(uniqid(microtime())); // Sets a random ID for the session
    }
} else {
    session_regenerate_id(true); // Prevent's session fixation
   //session_id(sha1(uniqid(microtime())); // Sets a random ID for the session
    // Set the default values for the session
    setSessionDefaults();
    $_SESSION['ip'] = getIp(); // Saves the user's IP
    $_SESSION['user_agent_id'] = getUserAgentId(); // Saves the user's navigator
}
?>