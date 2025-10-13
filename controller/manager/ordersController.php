<?php
session_start();
require_once('../../model/manager/ordersModel.php');

if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "Manager") {
    header('Location: /login.php');
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $orders = getAllOrders();
    echo json_encode($orders);
    exit();
}

if ($method === 'PATCH') {
    $id = $_GET['id'] ?? 0;
    $data = json_decode(file_get_contents("php://input"), true);
    $newStatus = $data['to'] ?? '';

    if ($id <= 0 || empty($newStatus)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit();
    }

    $updated = updateOrderStatus($id, $newStatus);
    echo json_encode(['updated' => $updated]);
    exit();
}

http_response_code(405); 
echo json_encode(['error' => 'Method Not Allowed']);
