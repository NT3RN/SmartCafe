<?php
require_once(__DIR__ . "/../dbConnect.php");

/* ইমেইল থেকে customer_id বের করা */
function pref_getCustomerIdByEmail($email){
    $conn = getConnect();
    $sql  = "SELECT c.customer_id 
             FROM Users u 
             JOIN Customers c ON c.customer_id = u.user_id 
             WHERE u.email = ? LIMIT 1";
    $st = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($st, "s", $email);
    mysqli_stmt_execute($st);
    mysqli_stmt_bind_result($st, $cid);
    $cidVal = 0;
    if (mysqli_stmt_fetch($st)) $cidVal = (int)$cid;
    mysqli_stmt_close($st);
    mysqli_close($conn);
    return $cidVal;
}

/* সব preference */
function pref_all($customerId){
    $conn = getConnect();
    $sql  = "SELECT preference_id, preference_type, details, created_at
             FROM MealPreferences WHERE customer_id = ? ORDER BY preference_id DESC";
    $st = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($st, "i", $customerId);
    mysqli_stmt_execute($st);
    $res = mysqli_stmt_get_result($st);
    $rows = [];
    while($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    mysqli_stmt_close($st);
    mysqli_close($conn);
    return $rows;
}

/* Add new preference */
function pref_add($customerId, $type, $details){
    $conn = getConnect();
    $sql  = "INSERT INTO MealPreferences (customer_id, preference_type, details)
             VALUES (?,?,?)";
    $st = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($st, "iss", $customerId, $type, $details);
    $ok = mysqli_stmt_execute($st);
    mysqli_stmt_close($st);
    mysqli_close($conn);
    return $ok;
}

/* Delete preference */
function pref_delete($customerId, $prefId){
    $conn = getConnect();
    $sql  = "DELETE FROM MealPreferences 
             WHERE preference_id = ? AND customer_id = ?";
    $st = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($st, "ii", $prefId, $customerId);
    $ok = mysqli_stmt_execute($st);
    mysqli_stmt_close($st);
    mysqli_close($conn);
    return $ok;
}

/*  Update preference */
function pref_update($customerId, $prefId, $type, $details){
    $conn = getConnect();
    $sql  = "UPDATE MealPreferences
             SET preference_type = ?, details = ?
             WHERE preference_id = ? AND customer_id = ?";
    $st = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($st, "ssii", $type, $details, $prefId, $customerId);
    $ok = mysqli_stmt_execute($st);
    mysqli_stmt_close($st);
    mysqli_close($conn);
    return $ok;
}
