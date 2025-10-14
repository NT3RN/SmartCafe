<?php
session_start();

$isLoggedIn = (isset($_SESSION["email"]) && isset($_SESSION["role"]));
if (!$isLoggedIn || $_SESSION["role"] !== "Customer") {
    header("Location: /SmartCafe/view/login.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/customer/orderModel.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/dbConnect.php");

$conn = getConnect();

$email = '';
if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
}
$email = mysqli_real_escape_string($conn, $email);

$sql = "SELECT user_id FROM Users WHERE email='$email' AND role='Customer' LIMIT 1";
$res = mysqli_query($conn, $sql);

$row = null;
if ($res) {
    $row = mysqli_fetch_assoc($res);
}

mysqli_close($conn);

if (!is_array($row) || !isset($row['user_id'])) {
    header("Location: /SmartCafe/view/login.php");
    exit();
}

$customer_id = (int)$row['user_id'];

$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if ($action === 'cancel') {
    $oid = 0;
    if (isset($_GET['id'])) {
        $oid = (int)$_GET['id'];
    }

    $ok = cancelOrderIfPending($customer_id, $oid);

    $msg = '';
    if ($ok) {
        $msg = "Order #$oid cancelled.";
    } else {
        $msg = "Cancel failed.";
    }

    header("Location: /SmartCafe/view/customer/orders.php?msg=" . urlencode($msg));
    exit();
}

header("Location: /SmartCafe/view/customer/orders.php");
exit();
