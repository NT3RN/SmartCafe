<?php
require_once(__DIR__ . "/../dbConnect.php");


function getActiveMenuItems() {
    
    $conn = getConnect();

    $sql = "
        SELECT menu_item_id, name, description, price, image_url
        FROM menuitems
        WHERE available = 1
        ORDER BY menu_item_id DESC
    ";

    $result = mysqli_query($conn, $sql);

    
    $items = array();

    
    if ($result) {
        
        while (true) {
            $row = mysqli_fetch_assoc($result);
            if (!$row) {
                
                break;
            }
           
            $items[] = $row;
        }
    }

    mysqli_close($conn);

    return $items;
}



function getMenuItemById($id) {
    
    $id = (int)$id;

    $conn = getConnect();

    $sql = "
        SELECT menu_item_id, name, description, price, image_url
        FROM menuitems
        WHERE available = 1 AND menu_item_id = $id
        LIMIT 1
    ";

    $result = mysqli_query($conn, $sql);

    $row = null;

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {

            $row = null;
        }
    }

    mysqli_close($conn);

    return $row;
}
