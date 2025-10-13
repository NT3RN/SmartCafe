<?php
require_once('../../model/manager/menuModel.php');
$menu_items = getMenuItems();

foreach ($menu_items as $item) {
    echo '<div class="flex justify-between items-center border-b py-2">';
    echo '<div>';
    echo '<h3 class="font-semibold">' . htmlspecialchars($item['name']) . '</h3>';
    echo '<p class="text-sm text-gray-500">' . htmlspecialchars($item['description']) . '</p>';
    echo '<p class="text-lg font-semibold">' . number_format($item['price_cents'] / 100, 2) . '$</p>';
    echo '</div>';
    echo '<button class="btn btn-secondary" onclick="editMenuItem(' . $item['menu_item_id'] . ')">Edit</button>';
    echo '</div>';
}
?>
