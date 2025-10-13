<?php
require_once('../../model/dbConnect.php');

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
    $sql = "INSERT INTO inventory (menu_item_id, stock_quantity) VALUES (?, ?) ON DUPLICATE KEY UPDATE stock_quantity = stock_quantity + ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $menu_item_id, $quantity, $quantity);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}

function adjustStock($menu_item_id, $quantity) {
    $conn = getConnect();
    $sql = "UPDATE inventory SET stock_quantity = stock_quantity + ? WHERE menu_item_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $quantity, $menu_item_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}
?>
