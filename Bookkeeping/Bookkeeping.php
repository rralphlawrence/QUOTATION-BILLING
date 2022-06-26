<?php
     include "includes/header.php";
     include "includes/sidebar.php";
     include "includes/footer.php";
?>
<html>
    <body>
        
    </body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!---Icons for menu--->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--Css-links-->
    <link rel="stylesheet" href="styles/sidebar-menu.css">

    
    <title>MISOUT</title>
</head>
<body>
     <!--==========  HEADER NA MAHABA ==========-->
     <header class="header">
        <div class="header__container">
           <h5 class="date">April 1, 2022</h5>



            <div class="header__toggle">
                <i class='bx bx-menu-alt-left' id="header-toggle"></i>
            </div>
        </div>
    </header>

    <!--Nav-->
    <div class="nav" id="navbar">
        <nav class="nav__container">
            <div>
                <a href="#" class="nav__link nav__logo">
                   
                        <img class="profile-img" src="./img/Roivasquez.jpg" width="50" height="50" alt="">
                  
              
                    <span class="nav__logo-name">Roi Vincent Vasquez</span>
                
                </a>

                <div class="nav__list">
                    <div class="nav__items">
                        <h3 class="nav__subtitle">FEATURES</h3>
                 
                        <div class="nav__dropdown">
                            <a href="Home.html" class="nav__link">
                                <i class='bx bx-home bx-flip-horizontal nav__icon' ></i>
                                <span class="nav__name">Home</span>
                            </a>
                        </div>

                        <div class="nav__dropdown">
                            <a href="Profile.html" class="nav__link">
                                <i class='bx bx-image bx-flip-horizontal nav__icon' ></i>
                                <span class="nav__name">Profile</span>
                            </a>
                        </div>

                        <div class="nav__dropdown">
                            <a href="Accounting.html" class="nav__link">
                                <i class='bx bx-money-withdraw nav__icon'></i>
                                <span class="nav__name">Accounting</span>
                            </a>
                        </div>

                        <div class="nav__dropdown">
                            <a href="Bookkeeping.html" class="nav__link">
                                <i class='bx bxs-bank nav__icon'></i>
                                <span class="nav__name">Boookkeeping</span>
                                <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                            </a>

                            <div class="nav__dropdown-collapse">
                                <div class="nav__dropdown-content">
                                    <a href="#" class="nav__dropdown-item">Dashboard</a>
                                    <a href="#" class="nav__dropdown-item">Cashbook Entry</a>
                                    <a href="#" class="nav__dropdown-item">Income Statement</a>
                                    <a href="#" class="nav__dropdown-item">Cash Flow</a>
                                </div>
                            </div>
                        </div>

                        <div class="nav__dropdown">
                            <a href="History.html" class="nav__link">
                                <i class='bx bxs-report nav__icon' ></i>
                                <span class="nav__name">History</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="Logout.html" class="nav__link nav__logout">
                <i class='bx bx-log-out nav__icon' ></i>
                <span class="nav__name">Log Out</span>
            </a>
        </nav>
    </div>

    <!--========== CONTENTS ==========-->
    <main>
        <section>

                <div class="mis-logo">
                    <img src="./img/misoutlogo.png" alt="">
                    <h3 class="mis-title">ALWAYS THE FASTEST</h3>
                </div>
           
        </section>
    </main>
    <script src="JS/response.js"></script>
</body>
</html>