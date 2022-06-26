<?php
ob_start(); 
 include_once '../includes/dbprocess.php';
   
 if(isset($_SESSION['isLoggedin'])){

    if($_SESSION['user_role'] == 'Billing'){
        header("Location: ../Admin/Dashboard.php");
    }elseif($_SESSION['user_role'] == 'Administrator'){
        header("Location: ../CEO/dashboard.php");
    }
        
}else{
  header("Location: ../index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./styles/B-main.css"/>
        <link rel="stylesheet" href="./styles/sidebar-menu.css">
        <!--========== ICONS NG MENU ==========-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
        <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
        <script src="../js/sweetalert.min.js"></script>
       
        
        <link rel="shortcut icon" type="image/png " href="../img/misoutlogo.png">
        


        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
        <link rel="stylesheet" href="../styles/sweetalert.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
        <!--========== CSS KINKS ==========-->
       


         <!-- CHARTS -->
         <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>

        <title>MISOUT SYSTEM</title>
    </head>



   <body>
  <header class="header">
            <div class="header__container">
            <span></span>
               <h5 class="date"><?php echo $_SESSION ['user_designation']?></h5>   
                <div class="header__toggle">
                    <i class='bx bx-menu-alt-left' id="header-toggle"></i>
                </div>
            </div>
        </header>
        </body>
  </html>