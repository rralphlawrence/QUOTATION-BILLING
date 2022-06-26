<?php include_once './includes/dbprocess.php'; 



if(isset($_SESSION['isLoggedin'])){

    if($_SESSION['user_role'] == 'Bookkeeping'){
        header("Location: ./Bookkeeping/Dashboard.php");
    }elseif($_SESSION['user_role'] == 'Billing'){
        header("Location: ./Admin/Dashboard.php");
    }else{
        header("Location: ./CEO/dashboard.php");
    }
        
 }else{
     
 }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/png " href="img/misoutlogo.png">
    <script src="js/sweetalert.min.js"></script>
    <link rel="stylesheet" href="style/sweetalert.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
    <title>Misout</title>
</head>
<body>
    <div class="container">    
        <div class="form-container">
            <div class="signin-signup">  
                <form action="./includes/dbprocess.php" method="POST" class="sign-in-form">
                    <img src="img/misoutlogo.png" style="width:220px;height:190px;" alt="">
                    <br>
                    <h2 class="title">SIGN IN</h2>
                    <div class="input-field">
                    <i class="fas fa-user"></i>
                        <input name="uname" type="text" placeholder="Username" required>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                         <input name="psw" type="password" placeholder="Password" required>
                    </div>
                    
                        <input type="submit" value="Login" name="btn-login" class="btn solid">
                </form>
            </div>
        </div>
        <div class="panels-container">
            <div class="panel left-panel">
            <div class="content" >
                <h1>ALWAYS</h1>
                <h1>the</h1>
                <h1>FASTEST</h1>
            </div>
                <img src="img/multi.svg" class="image" alt="">
            </div>            
        </div>
    </div>
</body>
</html>

<?php
           if (isset($_SESSION['response']) && $_SESSION['response'] !='') { ?>

<script>
swal({
    title: "<?php echo $_SESSION['response']?>",
    icon: "<?php echo $_SESSION['res_type']?>",
    button: "Done",
});
</script>

<?php
     unset($_SESSION['res_type']); 
    unset($_SESSION['response']); }
?>