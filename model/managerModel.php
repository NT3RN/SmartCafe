<?php
require_once("dbConnect.php");

function getAllManagers() {
    $conn = getConnect();
    $sql = "SELECT u.user_id, u.username, u.email, u.created_at, m.salary FROM Users u JOIN Managers m ON u.user_id = m.manager_id WHERE u.role = 'Manager' ORDER BY u.created_at DESC";
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
    $salary = floatval($salary);
    $role = 'Manager';
    
    mysqli_begin_transaction($conn); 
    try {
        $sql = "INSERT INTO Users (username, email, password, role, security_question, security_answer) VALUES ('$username', '$email', '$password', '$role', '$security_question', '$security_answer')";
        
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Failed to insert user");
        }
        
        $user_id = mysqli_insert_id($conn);
        
        $managerSql = "UPDATE Managers SET salary = $salary WHERE manager_id = $user_id";
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
    $sql = "SELECT 1 FROM Users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    $exists = mysqli_num_rows($result) > 0;
    mysqli_close($conn);
    
    return $exists;
}

function deleteManager($user_id) {
    $conn = getConnect();
    $sql = "DELETE FROM Users WHERE user_id='$user_id' AND role='Manager'";
    $result = mysqli_query($conn, $sql);
    
    $affected = mysqli_affected_rows($conn);
    mysqli_close($conn);
    
    return $affected > 0;
}
?>