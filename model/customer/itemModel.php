<?php
require_once(__DIR__ . "/../dbConnect.php");


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
