<?php
session_start();

/* অথ গার্ড (AUTH GUARD): শুধুমাত্র লগইন করা গ্রাহকরা (Customers) এই অংশে প্রবেশ করতে পারবেন।
অর্থাৎ, যদি ব্যবহারকারী লগইন না করে থাকে অথবা তার ভূমিকা (role) "Customer" না হয়, তবে তাকে লগইন পেজে পাঠানো হবে। */

$isLoggedIn = (isset($_SESSION["email"]) && isset($_SESSION["role"]));
if (!$isLoggedIn || $_SESSION["role"] !== "Customer") {
    header("Location: /SmartCafe/view/login.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/customer/itemModel.php");

/* সেশন (Session)-এ কার্ট থাকা নিশ্চিত করা:
ব্যহারকারীর সেশনে একটি কার্ট (cart) থাকতে হবে। যদি না থাকে, তবে সেটি তৈরি করতে হবে।
প্রতিটি কার্ট আইটেমের মধ্যে থাকবে নিচের তথ্যগুলো —
menu_item_id : মেনু আইটেমের আইডি
name : আইটেমের নাম
price : প্রতি ইউনিটের দাম
qty : পরিমাণ (quantity) */

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}

/* 
    Detect action (POST takes priority over GET)
 */
$action = '';
if (isset($_POST['action'])) {
    $action = $_POST['action'];
} else {
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = '';
    }
}

/*
    ADD ITEM TO CART
 */
if ($action === 'add') {
    // Read menu_item_id
    $id = 0;
    if (isset($_POST['menu_item_id'])) {
        $id = (int)$_POST['menu_item_id'];
    }

    // Read qty with minimum = 1
    $qty = 1;
    if (isset($_POST['qty'])) {
        $qty = (int)$_POST['qty'];
        if ($qty < 1) {
            $qty = 1;
        }
    }

    $item = null;

    if ($id > 0) {
        // Fetch DB item
        $db = getMenuItemById($id);
        if ($db) {
            $tmpId = 0;
            if (isset($db['menu_item_id'])) {
                $tmpId = (int)$db['menu_item_id'];
            }

            $tmpName = '';
            if (isset($db['name'])) {
                $tmpName = $db['name'];
            }

            $tmpPrice = 0.0;
            if (isset($db['price'])) {
                $tmpPrice = (float)$db['price'];
            }

            $item = array(
                'menu_item_id' => $tmpId,
                'name'         => $tmpName,
                'price'        => $tmpPrice
            );
        }
    } else {
        // Default item (posted via form)
        $postedName = '';
        if (isset($_POST['name'])) {
            $postedName = trim($_POST['name']);
        }

        $hasPostedPrice = false;
        $postedPrice = 0.0;
        if (isset($_POST['price'])) {
            $hasPostedPrice = true;
            $postedPrice = (float)$_POST['price'];
        }

        if ($postedName !== '' && $hasPostedPrice) {
            $item = array(
                'menu_item_id' => $id, // negative or zero for default items
                'name'         => $postedName,
                'price'        => (float)$postedPrice
            );
        }
    }

    //  Item not found → redirect with message
    $itemExists = (is_array($item) && isset($item['name']) && isset($item['price']));
    if (!$itemExists) {
        header("Location: /SmartCafe/view/customer/menu.php?msg=" . urlencode("Item not available."));
        exit();
    }

    //  If already in cart, increase quantity
    $found = false;
    foreach ($_SESSION["cart"] as &$c) {
        // DB item → match by ID
        if ($item['menu_item_id'] > 0) {
            if (isset($c['menu_item_id']) && $c['menu_item_id'] == $item['menu_item_id']) {
                if (isset($c['qty'])) {
                    $c['qty'] = (int)$c['qty'] + $qty;
                } else {
                    $c['qty'] = $qty;
                }
                $found = true;
                break;
            }
        } else {
            // Default item → match by name (case-insensitive) and non-positive ID
            $cName = '';
            if (isset($c['name'])) {
                $cName = $c['name'];
            }
            $cId = 0;
            if (isset($c['menu_item_id'])) {
                $cId = (int)$c['menu_item_id'];
            }

            if (strtolower($cName) === strtolower($item['name']) && $cId <= 0) {
                if (isset($c['qty'])) {
                    $c['qty'] = (int)$c['qty'] + $qty;
                } else {
                    $c['qty'] = $qty;
                }
                $found = true;
                break;
            }
        }
    }
    unset($c); // break reference safety

    // If new item → add to cart
    if (!$found) {
        $_SESSION["cart"][] = array(
            'menu_item_id' => $item['menu_item_id'],
            'name'         => $item['name'],
            'price'        => (float)$item['price'],
            'qty'          => $qty
        );
    }

    header("Location: /SmartCafe/view/customer/cart.php");
    exit();
}

/*  UPDATE CART QUANTITIES
 */
if ($action === 'update') {
    $hasQtyArray = (isset($_POST['qty']) && is_array($_POST['qty']));
    if ($hasQtyArray) {
        foreach ($_POST['qty'] as $idx => $q) {
            if (isset($_SESSION["cart"][$idx])) {
                $newQty = (int)$q;
                if ($newQty < 1) {
                    $newQty = 1;
                }
                $_SESSION["cart"][$idx]['qty'] = $newQty;
            }
        }
    }

    header("Location: /SmartCafe/view/customer/cart.php");
    exit();
}

/*  REMOVE SINGLE ITEM
 */
if ($action === 'remove') {
    $idx = -1;
    if (isset($_GET['idx'])) {
        $idx = (int)$_GET['idx'];
    }

    if (isset($_SESSION["cart"][$idx])) {
        array_splice($_SESSION["cart"], $idx, 1);
    }

    header("Location: /SmartCafe/view/customer/cart.php");
    exit();
}

/* CLEAR ENTIRE CART
 */
if ($action === 'clear') {
    $_SESSION["cart"] = array();
    header("Location: /SmartCafe/view/customer/cart.php");
    exit();
}

/*  DEFAULT FALLBACK (No valid action)
 */
header("Location: /SmartCafe/view/customer/menu.php");
exit();
