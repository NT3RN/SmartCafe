<?php
session_start();
if (!isset($_SESSION["email"], $_SESSION["role"]) || $_SESSION["role"] !== "Customer") {
    header("Location: /SmartCafe/view/login.php"); exit();
}

require_once($_SERVER['DOCUMENT_ROOT']."/SmartCafe/model/customer/preferenceModel.php");

$customerId = $_SESSION['customer_id'] ?? 0;
if (!$customerId){ $customerId = pref_getCustomerIdByEmail($_SESSION['email']); }

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add') {
    $type = trim($_POST['preference_type'] ?? '');
    $det  = trim($_POST['details'] ?? '');
    if ($type === '') {
        header("Location: /SmartCafe/view/customer/preferences.php?msg=".urlencode("Type required")); exit();
    }
    $ok = pref_add($customerId, $type, $det);
    $m  = $ok ? "Preference added" : "Failed to add";
    header("Location: /SmartCafe/view/customer/preferences.php?msg=".urlencode($m)); exit();
}

if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    $ok = pref_delete($customerId, $id);
    $m  = $ok ? "Preference removed" : "Failed to remove";
    header("Location: /SmartCafe/view/customer/preferences.php?msg=".urlencode($m)); exit();
}

/*  Update handler */
if ($action === 'update') {
    $id   = (int)($_POST['pref_id'] ?? 0);
    $type = trim($_POST['preference_type'] ?? '');
    $det  = trim($_POST['details'] ?? '');
    if ($id <= 0 || $type === '') {
        header("Location: /SmartCafe/view/customer/preferences.php?msg=".urlencode("Invalid input")); exit();
    }
    $ok = pref_update($customerId, $id, $type, $det);
    $m  = $ok ? "Preference updated successfully" : "Failed to update";
    header("Location: /SmartCafe/view/customer/preferences.php?msg=".urlencode($m)); exit();
}

header("Location: /SmartCafe/view/customer/preferences.php");
