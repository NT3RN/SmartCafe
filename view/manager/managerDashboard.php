<?php
session_start();
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "Manager") {
    header('Location: /login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link href="/view/css/managerDashboard.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg">
            <div class="border-b">
                <ul class="flex space-x-4 p-4 text-lg font-semibold">
                    <li><a href="#" class="tab-button" data-tab="menu">Menu</a></li>
                    <li><a href="#" class="tab-button" data-tab="inventory">Inventory</a></li>
                    <li><a href="#" class="tab-button" data-tab="orders">Orders</a></li>
                </ul>
            </div>

            <div id="menu" class="tab-content p-4">
                <h2 class="text-2xl mb-4">Menu Management</h2>
                <div id="menu-items">
                    <?php include 'menu_items.php'; ?>
                </div>
                <button class="btn btn-primary" id="add-menu-item">Add New Item</button>
            </div>

            <div id="inventory" class="tab-content p-4 hidden">
                <h2 class="text-2xl mb-4">Inventory Management</h2>
                <div id="inventory-items">
                    <?php include 'inventory_items.php'; ?>
                </div>
                <button class="btn btn-primary" id="restock-item">Restock Item</button>
            </div>

            <div id="orders" class="tab-content p-4 hidden">
                <h2 class="text-2xl mb-4">Order Management</h2>
                <div id="order-items">
                    <?php include 'order_items.php'; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="/view/js/managerDashboard.js"></script>
</body>
</html>
