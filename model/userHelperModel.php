<?php
require_once("dbConnect.php");

function getUserByEmail($email) {
    $conn = getConnect();
    $sql  = "SELECT user_id, username, email, role FROM Users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $row ?: null;
}


function getCustomerIdByEmail($email) {
    $u = getUserByEmail($email);
    if (!$u || $u['role'] !== 'Customer') return null;
    return (int)$u['user_id'];
}
