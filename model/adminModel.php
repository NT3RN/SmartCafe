<?php
require_once("dbConnect.php");

function getAllAdmins() {
    $conn = getConnect();
    $sql = "SELECT user_id, username, email, created_at FROM users WHERE role = 'Admin' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    
    $admins = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $admins[] = $row;
    }
    mysqli_close($conn);
    return $admins;
}

function addAdmin($username, $email, $password, $security_question, $security_answer) {
    $conn = getConnect();
    $role = 'Admin';
    
    $sql = "INSERT INTO users (username, email, password, role, security_question, security_answer) VALUES ('$username', '$email', '$password', '$role', '$security_question', '$security_answer')";
    
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    return $result;
}

function checkEmailExists($email) {
    $conn = getConnect();
    $sql = "SELECT 1 FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    $exists = mysqli_num_rows($result) > 0;
    mysqli_close($conn);
    
    return $exists;
}

function deleteAdmin($user_id) {
    $conn = getConnect();
    $sql = "DELETE FROM users WHERE user_id='$user_id' AND role='Admin'";
    $result = mysqli_query($conn, $sql);
    
    $affected = mysqli_affected_rows($conn);
    mysqli_close($conn);
    
    return $affected > 0;
}

function getAdminProfile($user_id) {
    $conn = getConnect();
    $sql = "SELECT user_id, username, email, security_question, security_answer, created_at FROM users WHERE user_id='$user_id' AND role='Admin'";
    $result = mysqli_query($conn, $sql);
    
    $profile = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    
    return $profile;
}

function verifyCurrentPassword($user_id, $current_password) {
    $conn = getConnect();
    $sql = "SELECT 1 FROM users WHERE user_id='$user_id' AND password='$current_password'";
    $result = mysqli_query($conn, $sql);
    
    $valid = mysqli_num_rows($result) > 0;
    mysqli_close($conn);
    
    return $valid;
}

function checkEmailExistsForOtherUser($email, $user_id) {
    $conn = getConnect();
    $sql = "SELECT 1 FROM users WHERE email='$email' AND user_id != '$user_id'";
    $result = mysqli_query($conn, $sql);
    
    $exists = mysqli_num_rows($result) > 0;
    mysqli_close($conn);
    
    return $exists;
}

function updateAdminProfile($user_id, $username, $email, $new_password, $security_question, $security_answer) {
    $conn = getConnect();

    if (!empty($new_password)) {
    $sql = "UPDATE users SET username='$username', email='$email', password='$new_password', security_question='$security_question', security_answer='$security_answer' WHERE user_id='$user_id' AND role='Admin'";
    } else {
    $sql = "UPDATE users SET username='$username', email='$email', security_question='$security_question', security_answer='$security_answer' WHERE user_id='$user_id' AND role='Admin'";
    }
    
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    return $result;
}
?>