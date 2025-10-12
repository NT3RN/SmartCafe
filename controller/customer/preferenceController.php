<?php
session_start();

if (!isset($_SESSION["email"], $_SESSION["role"]) || $_SESSION["role"] !== "Customer") {
  header("Location: /SmartCafe/view/login.php"); exit();
}

$customer_id = (int)($_SESSION["customer_id"] ?? 0);
if ($customer_id <= 0) { header("Location: /SmartCafe/view/login.php"); exit(); }

require_once(__DIR__ . "/../../model/preferenceModel.php");

$action = $_GET['action'] ?? 'index';

function go(string $qs = ''): void {
  $base = "/SmartCafe/controller/customer/preferenceController.php";
  header("Location: $base" . ($qs ? ("?" . $qs) : ""));
  exit();
}

if ($action === 'index') {
  $prefs = pref_all_by_customer($customer_id);
  $msg   = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
  include(__DIR__ . "/../../view/customer/preferences.php");
  exit();
}

if ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $item  = trim($_POST['item_name'] ?? '');
  $spice = $_POST['spice_level'] ?? 'medium';
  $fav   = isset($_POST['is_favorite']) ? 1 : 0;
  $notes = trim($_POST['notes'] ?? '');

  if ($item === '') go("action=index&msg=" . urlencode("Item name cannot be empty"));

  [$ok, $err] = pref_create($customer_id, $item, $spice, $fav, $notes);
  go("action=index&msg=" . urlencode($ok ? "Preference added" : ("Failed: $err")));
}

if ($action === 'edit') {
  $id   = (int)($_GET['id'] ?? 0);
  $pref = pref_get($id, $customer_id);
  if (!$pref) go("action=index&msg=" . urlencode("Not found"));
  $msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
  include(__DIR__ . "/../../view/customer/preference_edit.php");
  exit();
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $id    = (int)($_POST['id'] ?? 0);
  $item  = trim($_POST['item_name'] ?? '');
  $spice = $_POST['spice_level'] ?? 'medium';
  $fav   = isset($_POST['is_favorite']) ? 1 : 0;
  $notes = trim($_POST['notes'] ?? '');

  if ($item === '') go("action=edit&id=$id&msg=" . urlencode("Item name cannot be empty"));

  [$ok, $err] = pref_update($id, $customer_id, $item, $spice, $fav, $notes);
  go("action=index&msg=" . urlencode($ok ? "Preference updated" : ("Failed: $err")));
}

if ($action === 'delete') {
  $id = (int)($_GET['id'] ?? 0);
  $ok = pref_delete($id, $customer_id);
  go("action=index&msg=" . urlencode($ok ? "Preference deleted" : "Delete failed"));
}

if ($action === 'toggle') {
  $id = (int)($_GET['id'] ?? 0);
  $ok = pref_toggle_fav($id, $customer_id);
  go("action=index&msg=" . urlencode($ok ? "Toggled favorite" : "Action failed"));
}

go("action=index");
