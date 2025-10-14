<?php
session_start();


if (!isset($_SESSION["email"], $_SESSION["role"]) || $_SESSION["role"] !== "Customer") {
    header("Location: /SmartCafe/view/login.php");
}
?>
<!doctype html>
<html lang="en">
<head>
  <title>Customer Dashboard - SmartCafe</title>
  <link rel="stylesheet" href="/SmartCafe/view/css/customer.css">
</head>
<body>

<header class="topbar">
  <div class="brand">SmartCafe</div>
  <nav>
    <a href="/SmartCafe/view/customer/menu.php">Menu</a>
    <a href="/SmartCafe/view/customer/cart.php">Cart</a>
    <a href="/SmartCafe/view/customer/orders.php">My Orders</a>
    <a  href="/SmartCafe/view/customer/preferences.php">Preferences</a> <!-- ⬅️ নতুন -->
    <a href="/SmartCafe/view/logout.php">Logout</a>
  </nav>
</header>


<main class="container">
  <h1>Welcome 👋</h1>
  <p>Browse menu, add to cart, and pay with Card, bKash, or Cash.</p>
  <a class="btn" href="/SmartCafe/view/customer/menu.php">Browse Menu</a>
</main>

</body>
</html>
