<?php
session_start();
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "Manager") {
    header('Location: /SmartCafe/view/login.php');
    exit();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Manager Dashboard - SmartCafe</title>
  <link rel="icon" href="/SmartCafe/assets/logo.png">
  <link rel="stylesheet" href="/SmartCafe/view/css/managerDashboard.css">
</head>
<body>
  <header class="topbar">
    <div class="brand">SmartCafe</div>
    <nav>
      <a href="/SmartCafe/view/manager/managerDashboard.php" class="active">Dashboard</a>
      <a href="/SmartCafe/view/logout.php">Logout</a>
    </nav>
  </header>
  <main class="container">
    <h1>Manager Dashboard</h1>
    <p>Use the API endpoints to manage menu, inventory, and orders.</p>
    <ul>
      <li>GET /SmartCafe/controller/menuController.php</li>
      <li>POST /SmartCafe/controller/menuController.php</li>
      <li>PATCH /SmartCafe/controller/menuController.php?id={menu_item_id}</li>
      <li>DELETE /SmartCafe/controller/menuController.php?id={menu_item_id}</li>
      <li>GET /SmartCafe/controller/inventoryController.php?lowStockOnly=1</li>
      <li>POST /SmartCafe/controller/inventoryController.php?action=receive&menu_item_id={id}</li>
      <li>POST /SmartCafe/controller/inventoryController.php?action=adjust&menu_item_id={id}</li>
      <li>GET /SmartCafe/controller/ordersController.php</li>
      <li>PATCH /SmartCafe/controller/ordersController.php?id={order_id}</li>
    </ul>
  </main>
</body>
</html>