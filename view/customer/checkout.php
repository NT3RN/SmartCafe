<?php
session_start();


$isLoggedIn = (isset($_SESSION["email"]) && isset($_SESSION["role"]));
if (!$isLoggedIn || $_SESSION["role"] !== "Customer") {
    header("Location: /SmartCafe/view/login.php");
}

$cart = [];
if (isset($_SESSION["cart"])) {
    $cart = $_SESSION["cart"];
}

$sum = 0.0;
for ($i = 0; $i < count($cart); $i++) {
    $item = $cart[$i];

    $price = 0.0;
    if (isset($item['price'])) {
        $price = (float)$item['price'];
    }

    $qty = 0;
    if (isset($item['qty'])) {
        $qty = (int)$item['qty'];
    }

    $sum += ($price * $qty);
}


$msg = '';
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
}
?>
<!doctype html>
<html lang="en">
<head>
  <title>Checkout - SmartCafe</title>
  <link rel="stylesheet" href="/SmartCafe/view/css/customer.css">
</head>
<body>

<header class="topbar">
  <div class="brand">SmartCafe</div>
  <nav>
    <a href="/SmartCafe/view/customer/menu.php">Menu</a>
    <a href="/SmartCafe/view/customer/cart.php">Cart</a>
    <a href="/SmartCafe/view/customer/orders.php">My Orders</a>
    <a href="/SmartCafe/view/logout.php">Logout</a>
  </nav>
</header>

<main class="container">
  <h1>Checkout</h1>

  <?php if ($msg !== ''): ?>
    <div class="alert"><?php echo $msg; ?></div>
  <?php endif; ?>

  <?php if (empty($cart)): ?>
    <p>Your cart is empty. <a href="/SmartCafe/view/customer/menu.php">Browse menu</a></p>
  <?php else: ?>

    <div class="summary-card">
      <div class="row-between">
        <span>Payable total</span>
        <strong>৳ <?php echo number_format($sum, 2); ?></strong>
      </div>

      <form method="post" action="/SmartCafe/controller/customer/checkoutController.php" class="pay-form">
        <div class="pay-options">
          <label class="pay-option">
            <input type="radio" name="payment_method" value="Card">
            <img src="/SmartCafe/view/images/card.png" alt="Card">
            <span>Card</span>
          </label>

          <label class="pay-option">
            <input type="radio" name="payment_method" value="bKash">
            <img src="/SmartCafe/view/images/bkash.png" alt="bKash">
            <span>bKash</span>
          </label>

          <label class="pay-option">
            <input type="radio" name="payment_method" value="Cash">
            <img src="/SmartCafe/view/images/cash.png" alt="Cash">
            <span>Cash</span>
          </label>

          <label class="pay-option">
            <input type="radio" name="payment_method" value="Rocket">
            <img src="/SmartCafe/view/images/Rocket.png" alt="Rocket">
            <span>Rocket</span>
          </label>
        </div>

        

        <input type="hidden" name="pay_now" value="1">
        <button class="btn" type="submit">Pay Now</button>
      </form>
    </div>

  <?php endif; ?>
</main>

</body>
</html>
