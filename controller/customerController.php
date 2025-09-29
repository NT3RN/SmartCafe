<?php
require_once("../model/customerModel.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $customers = getAllCustomers();
    echo json_encode($customers);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $security_question = trim($_POST['security_question'] ?? '');
    $security_answer = trim($_POST['security_answer'] ?? '');

    if (!$username || !$email || !$password || !$security_question || !$security_answer) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        exit();
    }

    if (checkEmailExists($email)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email already exists']);
        exit();
    }

    if (addCustomer($username, $email, $password, $security_question, $security_answer)) {
        echo json_encode(['success' => true, 'message' => 'Customer added successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add customer']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $user_id = intval($_DELETE['user_id'] ?? 0);
    
    if ($user_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid user ID']);
        exit();
    }

    if (deleteCustomer($user_id)) {
        echo json_encode(['success' => true, 'message' => 'Customer deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Customer not found or failed to delete']);
    }
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
?>