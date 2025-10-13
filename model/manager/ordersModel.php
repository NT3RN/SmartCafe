<?php
require_once('../../model/dbConnect.php');

function getAllOrders() {
    $conn = getConnect(); 
    $sql = "SELECT order_id, order_status FROM orders ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $orders = [];

    while ($order = mysqli_fetch_assoc($res)) {
        $orders[] = $order;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $orders;
}

function getOrdersByStatus($status) {
    $conn = getConnect();
    $sql = "SELECT order_id, order_status FROM orders WHERE order_status = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $status);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $orders = [];

    while ($order = mysqli_fetch_assoc($res)) {
        $orders[] = $order;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $orders;
}

function updateOrderStatus($id, $newStatus) {
    $conn = getConnect();
    $sql = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $newStatus, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}
?>
