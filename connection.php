<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'BJTS');
$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

//function xssafe
function xssafe($data,$encoding='UTF-8'){
    return htmlspecialchars($data,ENT_QUOTES|ENT_HTML401|ENT_HTML401);
}

// function to clean output
function xecho($data){
    echo xssafe($data);
};
?>
