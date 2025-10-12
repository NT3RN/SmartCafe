<?php
require_once(__DIR__ . "/../dbConnect.php");

/*
   FUNCTION: getActiveMenuItems
উদ্দেশ্য (PURPOSE): যেসব মেনু আইটেমের available = 1, অর্থাৎ যেগুলো বর্তমানে সক্রিয় বা বিক্রয়ের জন্য উপলব্ধ, সেগুলো ডাটাবেস থেকে নিয়ে আসা।
ফলাফল (RETURNS): একটি অ্যারে (Array) যা একাধিক Associative Array ধারণ করে — প্রতিটি আইটেম একটি করে মেনু আইটেমের তথ্য (যেমন নাম, দাম, বর্ণনা ইত্যাদি) প্রতিনিধিত্ব করে।
  */
function getActiveMenuItems() {
    // Connect to database
    $conn = getConnect();

    // Define SQL query
    $sql = "
        SELECT menu_item_id, name, description, price, image_url
        FROM MenuItems
        WHERE available = 1
        ORDER BY menu_item_id DESC
    ";

    // Execute query
    $result = mysqli_query($conn, $sql);

    // Prepare array to store menu items
    $items = array();

    // Check if query returned a result
    if ($result) {
        // Fetch each row as an associative array
        while (true) {
            $row = mysqli_fetch_assoc($result);
            if (!$row) {
                // Stop when no more rows
                break;
            }
            // Add this row to the items array
            $items[] = $row;
        }
    }

    // Close the database connection
    mysqli_close($conn);

    // Return all fetched menu items
    return $items;
}

/* 
ফাংশন (FUNCTION): getMenuItemById
উদ্দেশ্য (PURPOSE): নির্দিষ্ট ID অনুযায়ী একটি সক্রিয় (active) মেনু আইটেম ডাটাবেস থেকে নিয়ে আসা।
প্যারামিটার (PARAM): $id (integer) — যে মেনু আইটেমের আইডি অনুসন্ধান করা হবে।
ফলাফল (RETURNS): একটি Associative Array (যেখানে মেনু আইটেমের তথ্য থাকবে) অথবা null, যদি আইডিটি পাওয়া না যায়। */

function getMenuItemById($id) {
    // Convert to integer to ensure safety
    $id = (int)$id;

    // Connect to database
    $conn = getConnect();

    // Build SQL query for a specific available item
    $sql = "
        SELECT menu_item_id, name, description, price, image_url
        FROM MenuItems
        WHERE available = 1 AND menu_item_id = $id
        LIMIT 1
    ";

    // Execute query
    $result = mysqli_query($conn, $sql);

    // Initialize variable to hold row
    $row = null;

    // If query successful and data returned
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            // No matching item found
            $row = null;
        }
    }

    // Close database connection
    mysqli_close($conn);

    // Return the fetched item (or null if not found)
    return $row;
}
