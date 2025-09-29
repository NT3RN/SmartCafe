<?php
require_once("../model/managerModel.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $managers = getAllManagers();
    echo json_encode($managers);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $security_question = trim($_POST['security_question'] ?? '');
    $security_answer = trim($_POST['security_answer'] ?? '');
    $salary = floatval($_POST['salary'] ?? 0);

    if (!$username || !$email || !$password || !$security_question || !$security_answer || $salary <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required and salary must be greater than 0']);
        exit();
    }

    if (checkEmailExists($email)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email already exists']);
        exit();
    }

    if (addManager($username, $email, $password, $security_question, $security_answer, $salary)) {
        echo json_encode(['success' => true, 'message' => 'Manager added successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add manager']);
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

    if (deleteManager($user_id)) {
        echo json_encode(['success' => true, 'message' => 'Manager deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Manager not found or failed to delete']);
    }
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
?>