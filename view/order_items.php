
<?php
require_once('../model/ordersModel.php');
$orders = getAllOrders();
foreach ($orders as $order) {
    echo '<div class="flex justify-between items-center border-b py-2">';
    echo '<div>';
    echo '<h3 class="font-semibold">Order #' . $order['order_id'] . '</h3>';
    echo '<p class="text-sm text-gray-500">Status: ' . htmlspecialchars($order['order_status']) . '</p>';
    echo '</div>';
    echo '<button class="btn btn-secondary" onclick="updateOrderStatus(' . $order['order_id'] . ')">Update Status</button>';
    echo '</div>';
}
?>
