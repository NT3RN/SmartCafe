<?php
require_once(__DIR__ . "/../dbConnect.php");

/* ফাংশন (FUNCTION): createPayment
উদ্দেশ্য (PURPOSE): নির্দিষ্ট একটি অর্ডার-এর জন্য পেমেন্ট রেকর্ড সংরক্ষণ করা।
প্যারামিটারসমূহ (PARAMS):
$order_id : যে অর্ডারের পেমেন্ট দেওয়া হচ্ছে তার আইডি
$amount : মোট প্রদেয় অর্থের পরিমাণ
$method : পেমেন্ট পদ্ধতি (যেমন — Cash, Card, অথবা MobilePayment)
ফলাফল (RETURNS): সফলভাবে সংরক্ষণ হলে true, ব্যর্থ হলে false রিটার্ন করবে। */

function createPayment($order_id, $amount, $method) {
    // Convert and validate types
    $order_id = (int)$order_id;
    $amount   = (float)$amount;

    /* -------------------------------
       Step 1: Normalize payment method
       ------------------------------- */
    $methodClean = 'Cash'; // Default

    if ($method === 'Card') {
        $methodClean = 'Card';
    } else {
        if ($method === 'MobilePayment') {
            $methodClean = 'MobilePayment';
        } else {
            $methodClean = 'Cash';
        }
    }

    /*
       Step 2: Connect to the database
        */
    $conn = getConnect();

    /* -
       Step 3: Prepare SQL statement
        */
    $sql = "
        INSERT INTO Payments (order_id, amount, payment_method, payment_status, paid_at)
        VALUES ($order_id, $amount, '$methodClean', 'Paid', NOW())
    ";

    /*
       Step 4: Execute the SQL query
        */
    $queryResult = mysqli_query($conn, $sql);

    /* 
       Step 5: Check execution success
        */
    $isSuccess = false;
    if ($queryResult) {
        $isSuccess = true;
    } else {
        $isSuccess = false;
    }

    /* 
       Step 6: Close the DB connection
        */
    mysqli_close($conn);

    /*
       Step 7: Return result (true/false)
     */
    return $isSuccess;
}
