<?php
session_start();
require_once('../modelinventoryModel.php');

if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "Manager") {
    header('Location: /login.php');
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $lowStockOnly = $_GET['lowStockOnly'] === '1';
    $inventory_items = getInventoryItems($lowStockOnly);
    echo json_encode($inventory_items);
    exit();
}

if ($method === 'POST') {
    $action = $_GET['action'] ?? '';
    $menu_item_id = $_GET['menu_item_id'] ?? 0;

    if ($menu_item_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid menu item ID']);
        exit();
    }

    $data = json_decode(file_get_contents("php://input"), true);
    
    if ($action === 'receive') {
        $quantity = $data['quantity'] ?? 0;
        if ($quantity <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid quantity']);
            exit();
        }
        
        $received = restockItem($menu_item_id, $quantity);
        echo json_encode(['received' => $received]);
        exit();
    }

    if ($action === 'adjust') {
        $quantity = $data['quantity'] ?? 0;
        if ($quantity === 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Quantity cannot be zero']);
            exit();
        }

        $adjusted = adjustStock($menu_item_id, $quantity);
        echo json_encode(['adjusted' => $adjusted]);
        exit();
    }

    http_response_code(400);
    echo json_encode(['error' => 'Unknown action']);
    exit();
}

http_response_code(405); 
echo json_encode(['error' => 'Method Not Allowed']);
