<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "SmartCafe";

function getConnect() {
    global $servername, $username, $password, $dbName;
    $conn = mysqli_connect($servername, $username, $password, $dbName);
    return $conn;
}
?>