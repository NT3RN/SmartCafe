<?php
session_start();

/* 
   1) AUTH GUARD (Customer only)
   */
$loggedIn = (isset($_SESSION["email"]) && isset($_SESSION["role"]));
if (!$loggedIn || $_SESSION["role"] !== "Customer") {
    header("Location: /SmartCafe/view/login.php");
    exit();
}

/* 
   2) CART SETUP
    */
$cart = [];
if (isset($_SESSION["cart"])) {
    $cart = $_SESSION["cart"];
}

$sum = 0.0;
foreach ($cart as $c) {
    $price = 0.0;
    if (isset($c['price'])) {
        $price = (float)$c['price'];
    }
    $qty = 0;
    if (isset($c['qty'])) {
        $qty = (int)$c['qty'];
    }
    $sum += ($price * $qty);
}

/* 
   3) FLASH/QUERY MESSAGE (safe)
    */
$msg = '';
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Cart - SmartCafe</title>
  <link rel="stylesheet" href="/SmartCafe/view/css/customer.css">
</head>
<body>

<header class="topbar">
  <div class="brand">SmartCafe</div>
  <nav>
    <a href="/SmartCafe/view/customer/menu.php">Menu</a>
    <a class="active" href="/SmartCafe/view/customer/cart.php">Cart</a>
    <a href="/SmartCafe/view/customer/orders.php">My Orders</a>
    <a href="/SmartCafe/view/logout.php">Logout</a>
  </nav>
</header>

<main class="container">
  <h1>Your Cart</h1>

  <?php if ($msg !== ''): ?>
    <div class="alert"><?php echo $msg; ?></div>
  <?php endif; ?>

  <?php if (empty($cart)): ?>
    <p>Your cart is empty. <a href="/SmartCafe/view/customer/menu.php">Browse menu</a></p>
  <?php else: ?>

    <form method="post" action="/SmartCafe/controller/customer/cartController.php">
      <input type="hidden" name="action" value="update">

      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $index = 0;
          foreach ($cart as $i => $c): 
              // Ensure fields exist and types are correct
              $name = '';
              if (isset($c['name'])) {
                  $name = htmlspecialchars($c['name']);
              }

              $price = 0.0;
              if (isset($c['price'])) {
                  $price = (float)$c['price'];
              }

              $qty = 1;
              if (isset($c['qty'])) {
                  $qty = (int)$c['qty'];
                  if ($qty < 1) {
                      $qty = 1;
                  }
              }

              $subtotal = $price * $qty;
              $index = $i + 1;
          ?>
            <tr>
              <td><?php echo $index; ?></td>
              <td><?php echo $name; ?></td>
              <td>৳ <?php echo number_format($price, 2); ?></td>
              <td>
                <input
                  type="number"
                  name="qty[<?php echo $i; ?>]"
                  min="1"
                  value="<?php echo $qty; ?>"
                  class="qty"
                >
              </td>
              <td>৳ <?php echo number_format($subtotal, 2); ?></td>
              <td>
                <a
                  class="link danger"
                  href="/SmartCafe/controller/customer/cartController.php?action=remove&idx=<?php echo $i; ?>"
                >
                  Remove
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="row-between">
        <button class="btn outline" type="submit">Update Cart</button>
        <a class="btn danger" href="/SmartCafe/controller/customer/cartController.php?action=clear">Clear Cart</a>
      </div>
    </form>

    <div class="total">
      <span>Total:</span>
      <strong>৳ <?php echo number_format($sum, 2); ?></strong>
    </div>

    <a class="btn" href="/SmartCafe/view/customer/checkout.php">Proceed to Checkout</a>

  <?php endif; ?>
</main>

</body>
</html>
