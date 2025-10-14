<?php
session_start();
require_once('../model/menuModel.php');

if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "Manager") {
    header('Location: /login.php');
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $menu_items = getMenuItems();
    echo json_encode($menu_items);
    exit();
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $price_cents = $data['price_cents'] ?? 0;
    
    if (empty($name) || empty($price_cents)) {
        http_response_code(400);
        echo json_encode(['error' => 'Name and price are required']);
        exit();
    }

    $menu_item_id = addMenuItem($name, $description, $price_cents);
    echo json_encode(['menu_item_id' => $menu_item_id]);
    exit();
}

if ($method === 'PATCH') {
    $id = $_GET['id'] ?? 0;
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid menu item ID']);
        exit();
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $updated = updateMenuItem($id, $data);
    echo json_encode(['updated' => $updated]);
    exit();
}

if ($method === 'DELETE') {
    $id = $_GET['id'] ?? 0;
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid menu item ID']);
        exit();
    }

    $deleted = deleteMenuItem($id);
    echo json_encode(['deleted' => $deleted]);
    exit();
}

http_response_code(405); 
echo json_encode(['error' => 'Method Not Allowed']);
