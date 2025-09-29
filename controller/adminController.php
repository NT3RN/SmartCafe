<?php
require_once("../model/adminModel.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'profile') {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }
        
        $profile = getAdminProfile($_SESSION['user_id']);
        if ($profile) {
            echo json_encode($profile);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Profile not found']);
        }
        exit();
    }
    
    $admins = getAllAdmins();
    echo json_encode($admins);
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

    if (addAdmin($username, $email, $password, $security_question, $security_answer)) {
        echo json_encode(['success' => true, 'message' => 'Admin added successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add admin']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $username = trim($_PUT['username'] ?? '');
    $email = trim($_PUT['email'] ?? '');
    $current_password = trim($_PUT['current_password'] ?? '');
    $new_password = trim($_PUT['new_password'] ?? '');
    $security_question = trim($_PUT['security_question'] ?? '');
    $security_answer = trim($_PUT['security_answer'] ?? '');

    if (!$username || !$email || !$current_password || !$security_question || !$security_answer) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        exit();
    }

    if (!verifyCurrentPassword($user_id, $current_password)) {
        http_response_code(400);
        echo json_encode(['error' => 'Current password is incorrect']);
        exit();
    }

    if (checkEmailExistsForOtherUser($email, $user_id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email already exists']);
        exit();
    }

    if (updateAdminProfile($user_id, $username, $email, $new_password, $security_question, $security_answer)) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update profile']);
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

    if (deleteAdmin($user_id)) {
        echo json_encode(['success' => true, 'message' => 'Admin deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Admin not found or failed to delete']);
    }
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
?>