<?php
require_once(__DIR__ . "/../dbConnect.php");

function pref_getCustomerIdByEmail($email) {
    $conn = getConnect();

    $sql = "
        SELECT c.customer_id
        FROM users u
        JOIN customers c ON c.customer_id = u.user_id
        WHERE u.email = ?
        LIMIT 1
    ";

    $st = mysqli_prepare($conn, $sql);

    if ($st) {
        mysqli_stmt_bind_param($st, "s", $email);
        mysqli_stmt_execute($st);
        mysqli_stmt_bind_result($st, $cid);

        $cidVal = 0;
        $hasRow = mysqli_stmt_fetch($st);

        if ($hasRow) {
            $cidVal = (int)$cid;
        } else {
            $cidVal = 0;
        }

        mysqli_stmt_close($st);
        mysqli_close($conn);
        return $cidVal;
    } else {
      
        mysqli_close($conn);
        return 0;
    }
}



function pref_all($customerId) {
    $conn = getConnect();

    $sql = "
        SELECT preference_id, preference_type, details
        FROM mealpreferences
        WHERE customer_id = ?
        ORDER BY preference_id DESC
    ";

    $st = mysqli_prepare($conn, $sql);

    if ($st) {
        mysqli_stmt_bind_param($st, "i", $customerId);
        mysqli_stmt_execute($st);

        $res = mysqli_stmt_get_result($st);

        $rows = array();
        if ($res) {
            while (true) {
                $r = mysqli_fetch_assoc($res);
                if (!$r) {
                    break;
                }
                $rows[] = $r;
            }
        }

        mysqli_stmt_close($st);
        mysqli_close($conn);

        return $rows;
    } else {
        
        mysqli_close($conn);
        return array();
    }
}


function pref_add($customerId, $type, $details) {
    $conn = getConnect();

    $sql = "
        INSERT INTO mealpreferences (customer_id, preference_type, details)
        VALUES (?,?,?)
    ";

    $st = mysqli_prepare($conn, $sql);

    if ($st) {
        mysqli_stmt_bind_param($st, "iss", $customerId, $type, $details);
        $ok = mysqli_stmt_execute($st);

        $isSuccess = false;
        if ($ok) {
            $isSuccess = true;
        } else {
            $isSuccess = false;
        }

        mysqli_stmt_close($st);
        mysqli_close($conn);

        return $isSuccess;
    } else {
     
        mysqli_close($conn);
        return false;
    }
}


function pref_delete($customerId, $prefId) {
    $conn = getConnect();

    $sql = "
        DELETE FROM mealpreferences
        WHERE preference_id = ? AND customer_id = ?
    ";

    $st = mysqli_prepare($conn, $sql);

    if ($st) {
        mysqli_stmt_bind_param($st, "ii", $prefId, $customerId);
        $ok = mysqli_stmt_execute($st);

        $isSuccess = false;
        if ($ok) {
            $isSuccess = true;
        } else {
            $isSuccess = false;
        }

        mysqli_stmt_close($st);
        mysqli_close($conn);

        return $isSuccess;
    } else {
        
        mysqli_close($conn);
        return false;
    }
}


function pref_update($customerId, $prefId, $type, $details) {
    $conn = getConnect();

    $sql = "
        UPDATE mealpreferences
        SET preference_type = ?, details = ?
        WHERE preference_id = ? AND customer_id = ?
    ";

    $st = mysqli_prepare($conn, $sql);

    if ($st) {
        mysqli_stmt_bind_param($st, "ssii", $type, $details, $prefId, $customerId);
        $ok = mysqli_stmt_execute($st);

        $isSuccess = false;
        if ($ok) {
            $isSuccess = true;
        } else {
            $isSuccess = false;
        }

        mysqli_stmt_close($st);
        mysqli_close($conn);

        return $isSuccess;
    } else {
        
        mysqli_close($conn);
        return false;
    }
}
