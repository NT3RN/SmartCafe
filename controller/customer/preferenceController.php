<?php
session_start();

/* Auth guard: Customer only
 */
$isLogged = (isset($_SESSION["email"]) && isset($_SESSION["role"]));
if (!$isLogged || $_SESSION["role"] !== "Customer") {
    header("Location: /SmartCafe/view/login.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/customer/preferenceModel.php");

/*  Resolve customerId without ?? shorthand
 */
$customerId = 0;
if (isset($_SESSION['customer_id'])) {
    $customerId = $_SESSION['customer_id'];
}
if (!$customerId) {
    $emailForLookup = '';
    if (isset($_SESSION['email'])) {
        $emailForLookup = $_SESSION['email'];
    }
    $customerId = pref_getCustomerIdByEmail($emailForLookup);
}

/*  Detect action (POST first, then GET)
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

/*  Add preference
 */
if ($action === 'add') {
    $type = '';
    if (isset($_POST['preference_type'])) {
        $type = trim($_POST['preference_type']);
    }

    $det = '';
    if (isset($_POST['details'])) {
        $det = trim($_POST['details']);
    }

    if ($type === '') {
        $msg = urlencode("Type required");
        header("Location: /SmartCafe/view/customer/preferences.php?msg=" . $msg);
        exit();
    }

    $ok = pref_add($customerId, $type, $det);

    $m = '';
    if ($ok) {
        $m = "Preference added";
    } else {
        $m = "Failed to add";
    }

    header("Location: /SmartCafe/view/customer/preferences.php?msg=" . urlencode($m));
    exit();
}

/* 
    Delete preference
 */
if ($action === 'delete') {
    $id = 0;
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
    }

    $ok = pref_delete($customerId, $id);

    $m = '';
    if ($ok) {
        $m = "Preference removed";
    } else {
        $m = "Failed to remove";
    }

    header("Location: /SmartCafe/view/customer/preferences.php?msg=" . urlencode($m));
    exit();
}

/*  Update preference
 */
if ($action === 'update') {
    $id = 0;
    if (isset($_POST['pref_id'])) {
        $id = (int)$_POST['pref_id'];
    }

    $type = '';
    if (isset($_POST['preference_type'])) {
        $type = trim($_POST['preference_type']);
    }

    $det = '';
    if (isset($_POST['details'])) {
        $det = trim($_POST['details']);
    }

    if ($id <= 0 || $type === '') {
        $msg = urlencode("Invalid input");
        header("Location: /SmartCafe/view/customer/preferences.php?msg=" . $msg);
        exit();
    }

    $ok = pref_update($customerId, $id, $type, $det);

    $m = '';
    if ($ok) {
        $m = "Preference updated successfully";
    } else {
        $m = "Failed to update";
    }

    header("Location: /SmartCafe/view/customer/preferences.php?msg=" . urlencode($m));
    exit();
}

/*  Default redirect
 */
header("Location: /SmartCafe/view/customer/preferences.php");
exit();
