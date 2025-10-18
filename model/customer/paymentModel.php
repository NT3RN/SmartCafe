<?php
require_once(__DIR__ . "/../dbConnect.php");



function createPayment($order_id, $amount, $method) {
    
    $order_id = (int)$order_id;
    $amount   = (float)$amount;

    $methodClean = 'Cash'; 

    if ($method === 'Card') {
        $methodClean = 'Card';
    } else {
        if ($method === 'MobilePayment') {
            $methodClean = 'MobilePayment';
        } else {
            $methodClean = 'Cash';
        }
    }

    $conn = getConnect();

    $sql = "
        INSERT INTO payments (order_id, amount, payment_method, payment_status, paid_at)
        VALUES ($order_id, $amount, '$methodClean', 'Paid', NOW())
    ";

    $queryResult = mysqli_query($conn, $sql);

    $isSuccess = false;
    if ($queryResult) {
        $isSuccess = true;
    } else {
        $isSuccess = false;
    }

    mysqli_close($conn);

    return $isSuccess;
}
