<?php
    include_once './includes/dbprocess.php';
    unset($_SESSION['user_realname']); 
    unset($_SESSION['user_role']); 
    unset($_SESSION['user_designation']); 
    unset($_SESSION['user_sex']); 
    unset($_SESSION['user_image']); 
    unset($_SESSION['user_email']); 
    unset($_SESSION['user_contact']);
    unset($_SESSION['isLoggedin']); 
    
    $_SESSION ['response'] = "Successfully Logout!";
    $_SESSION ['res_type']= "success";
    header("Location: index.php");
?>