<?php
require_once(__DIR__ . "/../dbConnect.php");

/*  FUNCTION: pref_getCustomerIdByEmail
   PURPOSE : ইমেইল থেকে কাস্টমারের আইডি (customer_id) বের করা
   PARAMS  : 
       $email (string) → কাস্টমারের ইমেইল ঠিকানা
   RETURNS : 
       integer customer_id → পাওয়া গেলে কাস্টমারের আইডি রিটার্ন করে, 
                             না পেলে 0 রিটার্ন করে */
function pref_getCustomerIdByEmail($email) {
    $conn = getConnect();

    $sql = "
        SELECT c.customer_id
        FROM Users u
        JOIN Customers c ON c.customer_id = u.user_id
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
        // Prepare failed
        mysqli_close($conn);
        return 0;
    }
}

/*  FUNCTION: pref_all
   PURPOSE : নির্দিষ্ট কাস্টমারের সব প্রেফারেন্স (পছন্দসমূহ) সংগ্রহ করা
   PARAMS  : 
       $customerId (int) → কাস্টমারের আইডি
   RETURNS : 
       array of preferences → কাস্টমারের সব প্রেফারেন্সের একটি অ্যারে রিটার্ন করে */

function pref_all($customerId) {
    $conn = getConnect();

    $sql = "
        SELECT preference_id, preference_type, details, created_at
        FROM MealPreferences
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
        // Prepare failed
        mysqli_close($conn);
        return array();
    }
}

/*   FUNCTION: pref_add
   PURPOSE : একটি নতুন প্রেফারেন্স (পছন্দ) রো বা রেকর্ড যোগ করা
   PARAMS  : 
       $customerId (int) → কাস্টমারের আইডি
       $type (string)    → প্রেফারেন্সের ধরন (যেমন Spicy, Vegan ইত্যাদি)
       $details (string) → প্রেফারেন্সের অতিরিক্ত বিবরণ
   RETURNS : 
       boolean success   → সফলভাবে যোগ হলে true, ব্যর্থ হলে false রিটার্ন করে */

function pref_add($customerId, $type, $details) {
    $conn = getConnect();

    $sql = "
        INSERT INTO MealPreferences (customer_id, preference_type, details)
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
        // Prepare failed
        mysqli_close($conn);
        return false;
    }
}

/*  FUNCTION: pref_delete
   PURPOSE : নির্দিষ্ট কাস্টমারের একটি প্রেফারেন্স (পছন্দ) রো বা রেকর্ড মুছে ফেলা
   PARAMS  : 
   $customerId (int) → কাস্টমারের আইডি
   $prefId (int)     → প্রেফারেন্সের আইডি
   RETURNS : 
       boolean success   → সফলভাবে মুছে ফেললে true, ব্যর্থ হলে false রিটার্ন করে */

function pref_delete($customerId, $prefId) {
    $conn = getConnect();

    $sql = "
        DELETE FROM MealPreferences
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
        // Prepare failed
        mysqli_close($conn);
        return false;
    }
}

/*  FUNCTION: pref_update
   PURPOSE : নির্দিষ্ট কাস্টমারের একটি প্রেফারেন্স (পছন্দ) রো বা রেকর্ড আপডেট করা
   PARAMS  : 
       $customerId (int)   → কাস্টমারের আইডি
       $prefId (int)       → প্রেফারেন্সের আইডি
       $type (string)      → প্রেফারেন্সের ধরন (যেমন Spicy, Vegan ইত্যাদি)
       $details (string)   → প্রেফারেন্সের অতিরিক্ত বিবরণ
   RETURNS : 
       boolean success     → কাজ সফল হলে true, ব্যর্থ হলে false রিটার্ন করে */

function pref_update($customerId, $prefId, $type, $details) {
    $conn = getConnect();

    $sql = "
        UPDATE MealPreferences
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
        // Prepare failed
        mysqli_close($conn);
        return false;
    }
}
