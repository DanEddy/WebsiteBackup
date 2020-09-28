<?php
/* Database credential. Assuming you are running MySQL server with default
settings (user 'root' with no password)*/
define('DB_SERVER', 'localhost');
define("DB_USERNAME","admin");
define("DB_PASSWORD",'admin');
define("DB_NAME", "data");

/* Attempt to link to MYSQL DATABASE */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check Connection
if ($link === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
