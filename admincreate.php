<?php
    require_once("model/adminModel.php");
    $adminadd = addAdmin('testadmin','testadmin@gmail.com', 'admin123', 'Are you admin?','Yes');
    if($adminadd){
        echo "Successfull";
    }
    else{
        echo "Failed"; 
    }
?>