<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "SmartCafe";

$con = new mysqli($servername, $username, $password, $dbName);
if ($con -> connect_error){
    die("Connection failed: ".$con->connect_error);
}
?>