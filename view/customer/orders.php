<?php
session_start();

$isLoggedIn = (isset($_SESSION["email"]) && isset($_SESSION["role"]));
if (!$isLoggedIn || $_SESSION["role"] !== "Customer") {
    header("Location: /SmartCafe/view/login.php");
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/dbConnect.php");
$conn = getConnect();

$email = '';
if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
}
$email = mysqli_real_escape_string($conn, $email);

$sql = "SELECT user_id FROM users WHERE email='$email' AND role='Customer' LIMIT 1";
$res = mysqli_query($conn, $sql);

$row = null;
if ($res) {
    $row = mysqli_fetch_assoc($res);
}

mysqli_close($conn);
if (!is_array($row) || !isset($row['user_id'])) {
    header("Location: /SmartCafe/view/login.php");
}

$customer_id = (int)$row['user_id'];

require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/customer/orderModel.php");
$orders = getOrdersByCustomer($customer_id);

$msg = '';
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
}
?>
<!doctype html>
<html lang="en">
<head>
  <title>My Orders - SmartCafe</title>
  <link rel="stylesheet" href="/SmartCafe/view/css/customer.css">
</head>
<body>

<header class="topbar">
  <div class="brand">SmartCafe</div>
  <nav>
    <a href="/SmartCafe/view/customer/menu.php">Menu</a>
    <a href="/SmartCafe/view/customer/cart.php">Cart</a>
    <a class="active" href="/SmartCafe/view/customer/orders.php">My Orders</a>
    <a href="/SmartCafe/view/logout.php">Logout</a>
  </nav>
</header>

<main class="container">
  <h1>My Orders</h1>

  <?php if ($msg !== ''): ?>
    <div class="alert"><?php echo $msg; ?></div>
  <?php endif; ?>

  <?php
  $hasOrders = (is_array($orders) && count($orders) > 0);
  if (!$hasOrders):
  ?>
    <p>No orders yet. <a href="/SmartCafe/view/customer/menu.php">Order something</a> 🍔</p>
  <?php else: ?>
    <table class="table">
      <thead>
        <tr>
          <th>#Order</th>
          <th>Status</th>
          <th>Placed At</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        for ($i = 0; $i < count($orders); $i++):
            $o = $orders[$i];

            $orderId = 0;
            if (isset($o['order_id'])) {
                $orderId = (int)$o['order_id'];
            }

            $statusRaw = '';
            if (isset($o['order_status'])) {
                $statusRaw = $o['order_status'];
            }
            $status = htmlspecialchars($statusRaw);

            $createdRaw = '';
            if (isset($o['created_at'])) {
                $createdRaw = $o['created_at'];
            }
            $createdAt = htmlspecialchars($createdRaw);

            $isPending = ($statusRaw === 'Pending');
        ?>
          <tr>
            <td>#<?php echo $orderId; ?></td>
            <td><?php echo $status; ?></td>
            <td><?php echo $createdAt; ?></td>
            <td>
              <?php if ($isPending): ?>
                <a
                  class="link danger"
                  href="/SmartCafe/controller/customer/orderController.php?action=cancel&id=<?php echo $orderId; ?>"
                >
                  Cancel
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endfor; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>

</body>
</html>
