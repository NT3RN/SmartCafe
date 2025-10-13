<?php
session_start();


$isLoggedIn = (isset($_SESSION["email"]) && isset($_SESSION["role"]));
if (!$isLoggedIn || $_SESSION["role"] !== "Customer") {
    header("Location: /SmartCafe/view/login.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/customer/itemModel.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/customer/orderModel.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/customer/paymentModel.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/dbConnect.php");


$cart = array();
if (isset($_SESSION["cart"])) {
    $cart = $_SESSION["cart"];
}

$isCartEmpty = (empty($cart));
if ($isCartEmpty) {
    $msg = urlencode("Your cart is empty.");
    header("Location: /SmartCafe/view/customer/cart.php?msg=" . $msg);
    exit();
}


$isPost = ($_SERVER["REQUEST_METHOD"] === "POST");
$hasPayNow = (isset($_POST["pay_now"]));

if ($isPost && $hasPayNow) {

    $validated = array();
    $total = 0.0;

    for ($i = 0; $i < count($cart); $i++) {
        $c = $cart[$i];

        $menuItemId = 0;
        if (isset($c['menu_item_id'])) {
            $menuItemId = (int)$c['menu_item_id'];
        }

        $db = getMenuItemById($menuItemId);

        if ($db) {
            $qty = 1;
            if (isset($c['qty'])) {
                $qty = (int)$c['qty'];
                if ($qty < 1) {
                    $qty = 1;
                }
            }

            $price = 0.0;
            if (isset($db['price'])) {
                $price = (float)$db['price'];
            }

            $dbId = 0;
            if (isset($db['menu_item_id'])) {
                $dbId = (int)$db['menu_item_id'];
            }

            $validated[] = array(
                'menu_item_id' => $dbId,
                'qty'          => $qty,
                'price'        => $price
            );

            $total += ($price * $qty);
        }
    }

    if (count($validated) === 0) {
        $msg = urlencode("Items unavailable now. Try again.");
        header("Location: /SmartCafe/view/customer/cart.php?msg=" . $msg);
        exit();
    }

    
    $ui = '';
    if (isset($_POST['payment_method'])) {
        $ui = $_POST['payment_method'];
    }

    $methodEnum = '';
    if ($ui === 'Card') {
        $methodEnum = 'Card';
    } else {
        if ($ui === 'bKash') {
            $methodEnum = 'MobilePayment';
        } else {
            if ($ui === 'Cash') {
                $methodEnum = 'Cash';
            } else {
                $msg = urlencode("Select a valid payment method.");
                header("Location: /SmartCafe/view/customer/checkout.php?msg=" . $msg);
                exit();
            }
        }
    }

    
    $conn = getConnect();

    $email = '';
    if (isset($_SESSION["email"])) {
        $email = $_SESSION["email"];
    }
    $safeEmail = mysqli_real_escape_string($conn, $email);

    $sql = "SELECT user_id FROM Users WHERE email='$safeEmail' AND role='Customer' LIMIT 1";
    $res = mysqli_query($conn, $sql);

    $row = null;
    if ($res) {
        $row = mysqli_fetch_assoc($res);
    }

    mysqli_close($conn);

    if (!is_array($row) || !isset($row['user_id'])) {
        header("Location: /SmartCafe/view/login.php");
        exit();
    }

    $customer_id = (int)$row['user_id'];

    $order_id = createOrderWithItems($customer_id, $validated);
    if (!$order_id) {
        $msg = urlencode("Failed to place order.");
        header("Location: /SmartCafe/view/customer/checkout.php?msg=" . $msg);
        exit();
    }

   
    $ok = createPayment($order_id, $total, $methodEnum);

    if ($ok) {
        $_SESSION["cart"] = array();
        $successMsg = urlencode("Payment successful. Order #$order_id placed.");
        header("Location: /SmartCafe/view/customer/orders.php?msg=" . $successMsg);
        exit();
    } else {
        $msg = urlencode("Payment failed. Try again.");
        header("Location: /SmartCafe/view/customer/checkout.php?msg=" . $msg);
        exit();
    }
}


header("Location: /SmartCafe/view/customer/checkout.php");
exit();
