<?php
    require_once ("userController.php");

    $emailErr ="";
    $passErr ="";
    $hasErr = false;
    if(($_SERVER["REQUEST_METHOD"]=="POST") && (isset($_POST["submit"])))
    {
        $email = trim($_POST["email"]);
        $pass = trim($_POST["password"]);

        if(empty($email)){
            $emailErr = "Email can't be empty";
            $hasErr = true;
        }

        if (empty($pass)) {
            $passErr = "Password can't be empty";
            $hasErr = true;
        }

        if($hasErr){
            header("Location: ../view/login.php?emailErr = $")
        }
    }

?>