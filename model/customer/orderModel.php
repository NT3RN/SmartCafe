<?php
require_once(__DIR__ . "/../dbConnect.php");


function createOrderWithItems($customer_id, $items) {
    // Example input: $items = [ ['menu_item_id'=>1, 'qty'=>2, 'price'=>150.0], ... ]

    // Validate items array
    if (empty($items)) {
        return false;
    }

    // Convert customer_id to integer (safety)
    $customer_id = (int)$customer_id;

    // Connect to database
    $conn = getConnect();

    /* 
       STEP 1: Create new order
        */
    $sqlOrder = "
        INSERT INTO Orders (customer_id, order_status)
        VALUES ($customer_id, 'Pending')
    ";

    $orderResult = mysqli_query($conn, $sqlOrder);

    if (!$orderResult) {
        // If order insertion failed, close connection and stop
        mysqli_close($conn);
        return false;
    }

    // Get the auto-generated order_id
    $order_id = mysqli_insert_id($conn);

    /* 
       STEP 2: Insert order items
       */
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

        // Ensure minimum quantity is 1
        if ($quantity < 1) {
            $quantity = 1;
        }

        // Build item insert SQL
        $sqlItem = "
            INSERT INTO OrderItems (order_id, menu_item_id, quantity)
            VALUES ($order_id, $menu_item_id, $quantity)
        ";

        $itemResult = mysqli_query($conn, $sqlItem);

        if (!$itemResult) {
            // If any item insert fails, close connection and stop
            mysqli_close($conn);
            return false;
        }
    }

    // Close DB connection
    mysqli_close($conn);

    // Return the created order ID
    return $order_id;
}



function getOrdersByCustomer($customer_id) {
    // Ensure ID is an integer
    $customer_id = (int)$customer_id;

    // Connect to DB
    $conn = getConnect();

    // SQL query to get orders
    $sql = "
        SELECT order_id, order_status, created_at
        FROM Orders
        WHERE customer_id = $customer_id
        ORDER BY order_id DESC
    ";

    $result = mysqli_query($conn, $sql);

    // Initialize array to hold fetched rows
    $orders = array();

    if ($result) {
        while (true) {
            $row = mysqli_fetch_assoc($result);
            if (!$row) {
                // Stop loop when no more rows
                break;
            }
            $orders[] = $row;
        }
    }

    // Close DB connection
    mysqli_close($conn);

    // Return all fetched orders
    return $orders;
}



function cancelOrderIfPending($customer_id, $order_id) {
    // Type cast for safety
    $customer_id = (int)$customer_id;
    $order_id    = (int)$order_id;

    // Connect to DB
    $conn = getConnect();

    // SQL: update order status to 'Cancelled' only if it's 'Pending'
    $sql = "
        UPDATE Orders
        SET order_status = 'Cancelled'
        WHERE order_id = $order_id
          AND customer_id = $customer_id
          AND order_status = 'Pending'
    ";

    mysqli_query($conn, $sql);

    // Check if any row was affected (means cancel succeeded)
    $affectedRows = mysqli_affected_rows($conn);
    $isSuccess = false;

    if ($affectedRows > 0) {
        $isSuccess = true;
    }

    // Close DB connection
    mysqli_close($conn);

    // Return true/false based on success
    return $isSuccess;
}
