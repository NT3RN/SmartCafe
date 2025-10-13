<?php
require_once('../../model/dbConnect.php');

function getMenuItems() {
    $conn = getConnect();
    $sql = "SELECT menu_item_id, name, description, price, image_url, available, created_at 
            FROM menuitems 
            ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $items = [];
    while ($item = mysqli_fetch_assoc($res)) {
        $items[] = $item;
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $items;
}

function addMenuItem($name, $description, $price, $image_url = null, $available = 1) {
    $conn = getConnect();
    $sql = "INSERT INTO menuitems (name, description, price, image_url, available, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    // “d” for decimal/double, “s” for string, “i” for integer
    mysqli_stmt_bind_param($stmt, "ssdsi", $name, $description, $price, $image_url, $available);
    $ok = mysqli_stmt_execute($stmt);
    $menu_item_id = $ok ? mysqli_insert_id($conn) : null;
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $menu_item_id;
}

function updateMenuItem($id, $fields) {
    $conn = getConnect();
    $set = [];
    $types = '';
    $vals = [];

    if (isset($fields['name'])) {
        $set[] = 'name = ?';
        $types .= 's';
        $vals[] = $fields['name'];
    }
    if (isset($fields['description'])) {
        $set[] = 'description = ?';
        $types .= 's';
        $vals[] = $fields['description'];
    }
    if (isset($fields['price'])) {
        $set[] = 'price = ?';
        $types .= 'd';
        $vals[] = $fields['price'];
    }
    if (isset($fields['image_url'])) {
        $set[] = 'image_url = ?';
        $types .= 's';
        $vals[] = $fields['image_url'];
    }
    if (isset($fields['available'])) {
        $set[] = 'available = ?';
        $types .= 'i';
        $vals[] = $fields['available'];
    }

    if (empty($set)) {
        return false;
    }

    $sql = "UPDATE menuitems SET " . implode(", ", $set) . " WHERE menu_item_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    // Append type and value for the WHERE id
    mysqli_stmt_bind_param($stmt, $types . 'i', ...array_merge($vals, [$id]));
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}

function deleteMenuItem($id) {
    $conn = getConnect();
    $sql = "DELETE FROM menuitems WHERE menu_item_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}


?>
