<?php
$server = "localhost";
$database = "tuneshare";
$user = "root";
$password = "";

$successful = true;
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = mysqli_connect($server, $user, $password, $database);
}catch (Exception $exception) {
  echo "The database could not be reached successfully!";
  $successful = false;
}
?>
