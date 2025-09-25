<?php 
$servername = "localhost";
$username = "root";
$dbpassword = "";
$dbName = "smartcafe";

function getConnect() {
    global $servername, $username, $dbpassword, $dbName;
    $conn = mysqli_connect($servername, $username, $dbpassword, $dbName);
    
    return $conn;
}
?>