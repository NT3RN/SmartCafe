<?php
require_once('dbConnect.php');

function getInventoryItems($lowStockOnly = false) {
    $conn = getConnect();
    $sql = "SELECT i.menu_item_id, m.name, i.stock_quantity FROM inventory i LEFT JOIN menuitems m ON i.menu_item_id = m.menu_item_id";
    if ($lowStockOnly) {
        $sql .= " WHERE i.stock_quantity <= 0";
    }
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
function restockItem($menu_item_id, $quantity) {
    $conn = getConnect();
    // First try to update existing inventory row
    $sqlUpdate = "UPDATE inventory SET stock_quantity = stock_quantity + ?, last_restock_date = CURDATE() WHERE menu_item_id = ?";
    $stmt = mysqli_prepare($conn, $sqlUpdate);
    mysqli_stmt_bind_param($stmt, "ii", $quantity, $menu_item_id);
    mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($affected === 0) {
        // No existing row, insert a new one
        $sqlInsert = "INSERT INTO inventory (menu_item_id, stock_quantity, last_restock_date) VALUES (?, ?, CURDATE())";
        $stmt2 = mysqli_prepare($conn, $sqlInsert);
        mysqli_stmt_bind_param($stmt2, "ii", $menu_item_id, $quantity);
        $ok = mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
        mysqli_close($conn);
        return $ok;
    }

    mysqli_close($conn);
    return true;
}

function adjustStock($menu_item_id, $quantity) {
    $conn = getConnect();
    $sql = "UPDATE inventory SET stock_quantity = stock_quantity + ? WHERE menu_item_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $quantity, $menu_item_id);
    mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    if ($affected === 0 && $quantity > 0) {
        // create row if adjusting positive stock and none exists
        $sqlInsert = "INSERT INTO inventory (menu_item_id, stock_quantity) VALUES (?, ?)";
        $stmt2 = mysqli_prepare($conn, $sqlInsert);
        mysqli_stmt_bind_param($stmt2, "ii", $menu_item_id, $quantity);
        $ok = mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
        mysqli_close($conn);
        return $ok;
    }
    mysqli_close($conn);
    return ($affected >= 0);
}
?>
