<?php
/*
===============================================================================
-- Joomla Update System for paid extensions by Nikola Biskup 12.01.2019.
-- this file serves as a check point for the automatic update system
-- it works in tandem with downloadid.php (by Muhamed), user must first input download ID into the field inside your extension in order for this to work
-- some variables are mandetory for you to make it work with your extensions (file location, databse tables of your subscription system)
-- commented fields marked " -- FOR TESTING -- "  - are used for debugging your process (must remain commented for automatic update to work)
===============================================================================
*/

$file="/home/path/log.txt"; // log file
$datum = strftime("%d. %m. %Y - %T"); // set your own time and date format for log file
$ip =  $_SERVER['REMOTE_ADDR']; // take id from the server - must upgrade to real ip  <--------
$dlid = $_GET['dlid']; // get download id data from user - possible upgrade to check based on domain name

$servername = "localhost";  // set it to connect using joomla database connection (if possible)
$username = "database_user";
$password = "database_pwd";
$dbname = "database_name";

$log = $datum .' - '. $dlid .' - '. $ip;  // construct log entry
$log .= "\r\n";

file_put_contents($file, $log, FILE_APPEND | LOCK_EX);  // saves the log
// echo $log.'--- log saved ---' . PHP_EOL;   // -- FOR TESTING --

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT txn_id, user_id FROM xdce0_payperdownloadplus_payments WHERE txn_id ='$dlid' "; //database query - 1
$result = $conn->query($sql);

// if $sql querry is positive set variables for next query $sql2
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
//        echo "Transaction ID: " . $row["txn_id"]. " - User-ID: " . $row["user_id"];   // -- FOR TESTING --
        $tid =  $row["txn_id"];
        $userid = $row["user_id"];
    }
} else {
//    echo "0 results - Transaction";  // -- FOR TESTING --
}

//second query that determines is the user valid for update
$sql2 = "SELECT user_id, enabled FROM xdce0_payperdownloadplus_users_licenses WHERE user_id ='$userid' "; //database query - 2
$result2 = $conn->query($sql2);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result2->fetch_assoc()) {
//        echo " - User-ID2: " . $row["user_id"]. " - enabled: " . $row["enabled"]. " " . $row["lastname"]. "<br>";  // -- FOR TESTING --
        $enabled = $row["enabled"];
    }
} else {
//    echo "0 enabled users";  // -- FOR TESTING --
}
// echo "Enabled: ".$enabled;   // -- FOR TESTING --

$conn->close(); // that's all folks

// if user is valid for download send the file
if($enabled == 1) {

// set file location variables (must be updated with each new version - work on a plugin maybe? perhaps)
  $filename = "mod_module_pro-3.66.zip";
  $filepath = "/home/secure/path/";

  header("Content-type: application/x-zip");
  header("Content-Length: ".filesize($filepath.$filename));
  header("Cache-Control: private");
  header("Cache-Control: no-cache, must-revalidat, emax-age=0, no-store");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Accept-Ranges: bytes");
  header("Content-Description: File Transfer");
  header("Content-Disposition: attachment; filename=\"".$filename."\"");
  header("Content-Transfer-Encoding: binary");
  header("Pragma: private");

  readfile($filepath.$filename);

  ob_end_flush();

}
  else
{
  echo "no download for you!";  // -- FOR TESTING --
}

?>
