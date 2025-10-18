<?php
require_once("dbConnect.php");

function getAllManagers() {
    $conn = getConnect();
    $sql = "SELECT u.user_id, u.username, u.email, u.created_at, m.salary 
        FROM users u
        JOIN managers m ON u.user_id = m.manager_id
            WHERE u.role = 'Manager'
            ORDER BY u.created_at DESC";
    $result = mysqli_query($conn, $sql);
    
    $managers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $managers[] = $row;
    }
    
    mysqli_close($conn);
    return $managers;
}

function addManager($username, $email, $password, $security_question, $security_answer, $salary) {
    $conn = getConnect();
    
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    $security_question = mysqli_real_escape_string($conn, $security_question);
    $security_answer = mysqli_real_escape_string($conn, $security_answer);
    $salary = floatval($salary);
    $role = 'Manager';
    

    mysqli_begin_transaction($conn); 
    try {
    $sql = "INSERT INTO users (username, email, password, role, security_question, security_answer) 
                VALUES ('$username', '$email', '$password', '$role', '$security_question', '$security_answer')";
        
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Failed to insert user");
        }
        
        $user_id = mysqli_insert_id($conn);
        
    // Trigger creates row in managers; update salary afterwards
    $managerSql = "UPDATE managers SET salary = $salary WHERE manager_id = $user_id";
        if (!mysqli_query($conn, $managerSql)) {
            throw new Exception("Failed to update manager salary");
        }
        
        mysqli_commit($conn);
        mysqli_close($conn);
        return true;
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        mysqli_close($conn);
        return false;
    }
}

function checkEmailExists($email) {
    $conn = getConnect();
    $email = mysqli_real_escape_string($conn, $email);
    
    $sql = "SELECT 1 FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    $exists = mysqli_num_rows($result) > 0;
    mysqli_close($conn);
    
    return $exists;
}

function deleteManager($user_id) {
    $conn = getConnect();
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    $sql = "DELETE FROM users WHERE user_id='$user_id' AND role='Manager'";
    $result = mysqli_query($conn, $sql);
    
    $affected = mysqli_affected_rows($conn);
    mysqli_close($conn);
    
    return $affected > 0;
}
?>