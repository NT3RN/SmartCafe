<?php
require_once('../../model/manager/inventoryModel.php');
$inventory_items = getInventoryItems();

foreach ($inventory_items as $item) {
    echo '<div class="flex justify-between items-center border-b py-2">';
    echo '<div>';
    echo '<h3 class="font-semibold">' . htmlspecialchars($item['name']) . '</h3>';
    echo '<p class="text-sm text-gray-500">Stock: ' . $item['stock_quantity'] . '</p>';
    echo '</div>';
    echo '<button class="btn btn-secondary" onclick="restockItem(' . $item['menu_item_id'] . ')">Restock</button>';
    echo '</div>';
}
?>
