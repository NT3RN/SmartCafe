<?php
require_once(__DIR__ . "/../dbConnect.php");


function createOrderWithItems($customer_id, $items) {
   
    if (empty($items)) {
        return false;
    }

    $customer_id = (int)$customer_id;

    $conn = getConnect();

    $sqlOrder = "
        INSERT INTO orders (customer_id, order_status)
        VALUES ($customer_id, 'Pending')
    ";

    $orderResult = mysqli_query($conn, $sqlOrder);

    if (!$orderResult) {
        
        mysqli_close($conn);
        return false;
    }

    $order_id = mysqli_insert_id($conn);

    for ($i = 0; $i < count($items); $i++) {
        $it = $items[$i];

        $menu_item_id = 0;
        $quantity = 0;

        if (isset($it['menu_item_id'])) {
            $menu_item_id = (int)$it['menu_item_id'];
        }

        if (isset($it['qty'])) {
            $quantity = (int)$it['qty'];
        }

        if ($quantity < 1) {
            $quantity = 1;
        }

        $sqlItem = "
            INSERT INTO orderitems (order_id, menu_item_id, quantity)
            VALUES ($order_id, $menu_item_id, $quantity)
        ";

        $itemResult = mysqli_query($conn, $sqlItem);

        if (!$itemResult) {
           
            mysqli_close($conn);
            return false;
        }
    }

    mysqli_close($conn);

    return $order_id;
}



function getOrdersByCustomer($customer_id) {
   
    $customer_id = (int)$customer_id;

    $conn = getConnect();

    $sql = "
        SELECT order_id, order_status, created_at
        FROM orders
        WHERE customer_id = $customer_id
        ORDER BY order_id DESC
    ";

    $result = mysqli_query($conn, $sql);

    $orders = array();

    if ($result) {
        while (true) {
            $row = mysqli_fetch_assoc($result);
            if (!$row) {
                
                break;
            }
            $orders[] = $row;
        }
    }

    mysqli_close($conn);

    return $orders;
}



function cancelOrderIfPending($customer_id, $order_id) {
   
    $customer_id = (int)$customer_id;
    $order_id    = (int)$order_id;

    $conn = getConnect();

        $sql = "
                UPDATE orders
                SET order_status = 'Cancelled'
                WHERE order_id = $order_id
                    AND customer_id = $customer_id
                    AND order_status = 'Pending'
        ";

    mysqli_query($conn, $sql);

    $affectedRows = mysqli_affected_rows($conn);
    $isSuccess = false;

    if ($affectedRows > 0) {
        $isSuccess = true;
    }

    mysqli_close($conn);

    return $isSuccess;
}
