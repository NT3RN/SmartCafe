<?php
require_once("dbConnect.php");

function getAllCustomers() {
    $conn = getConnect();
    $sql = "SELECT user_id, username, email, created_at 
        FROM users 
            WHERE role = 'Customer'
            ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    
    $customers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }
    
    mysqli_close($conn);
    return $customers;
}

function addCustomer($username, $email, $password, $security_question, $security_answer) {
    $conn = getConnect();
    $role = 'Customer';
    
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

function deleteCustomer($user_id) {
    $conn = getConnect();

    $sql = "DELETE FROM users WHERE user_id='$user_id' AND role='Customer'";
    $result = mysqli_query($conn, $sql);
    
    $affected = mysqli_affected_rows($conn);
    mysqli_close($conn);
    
    return $affected > 0;
}
?>