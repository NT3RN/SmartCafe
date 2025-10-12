<?php
require_once(__DIR__ . "/../dbConnect.php");

/* ফাংশন (FUNCTION): createOrderWithItems
উদ্দেশ্য (PURPOSE): নতুন একটি অর্ডার রেকর্ড তৈরি করা এবং সেই অর্ডারের সকল আইটেম ডাটাবেসে সংরক্ষণ করা।
প্যারামিটারসমূহ (PARAMS):
$customer_id : যে গ্রাহক অর্ডার দিচ্ছেন তার আইডি
$items : একটি অ্যারে, যেখানে প্রতিটি আইটেমে থাকবে menu_item_id, qty, এবং price
ফলাফল (RETURNS): সফলভাবে সম্পন্ন হলে Order ID প্রদান করবে, অন্যথায় false রিটার্ন করবে। */

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

/* =ফাংশন (FUNCTION): getOrdersByCustomer
উদ্দেশ্য (PURPOSE): নির্দিষ্ট কোনো গ্রাহকের (customer) সবগুলো অর্ডার ডাটাবেস থেকে নিয়ে আসা।
প্যারামিটার (PARAMS): $customer_id (integer) — যে গ্রাহকের অর্ডারগুলো পাওয়া যাবে তার আইডি।
ফলাফল (RETURNS): একটি অ্যারে (Array), যেখানে প্রতিটি উপাদান একটি অর্ডার রেকর্ড (যার মধ্যে থাকবে id, status, এবং date)। */

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

/* 
ফাংশন (FUNCTION): cancelOrderIfPending
উদ্দেশ্য (PURPOSE): কোনো অর্ডার শুধুমাত্র তখনই বাতিল (cancel) করা যাবে, যখন তার status = 'Pending' থাকে।
প্যারামিটারসমূহ (PARAMS):
$customer_id : গ্রাহকের আইডি (যিনি অর্ডার দিয়েছেন)
$order_id : অর্ডারের আইডি (যেটি বাতিল করতে হবে)
ফলাফল (RETURNS): সফলভাবে বাতিল হলে true রিটার্ন করবে, অন্যথায় false প্রদান করবে। */

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
