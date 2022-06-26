<?php
 include_once '../includes/dbprocess.php';


?>
    <!--Nav-->
    <div class="nav" id="navbar">
        <nav class="nav__container">
            <div>
                <a href="#" class="nav__link nav__logo">
                        
                        <img class="profile-img" src= "../Bookkeeping/img/profile-picture/<?php echo $_SESSION ['user_image']; ?>" width="50" height="50" alt="">
                  
              
                    <span class="nav__logo-name"><?php echo $_SESSION ['user_realname']?></span>

                </a>

                <div class="nav__list">
                    <div class="nav__items">
                        <h3 class="nav__subtitle">FEATURES</h3>
                 
                        <div class="nav__dropdown">
                            <a href="Dashboard.php" class="nav__link">
                                <i class='bx bx-home bx-flip-horizontal nav__icon' ></i>
                                <span class="nav__name">DASHBOARD</span>
                            </a>
                        </div>

                        <div class="nav__dropdown">
                            <a href="Accounting.php" class="nav__link">
                                <i class='bx bx-pie-chart-alt-2 nav__icon'></i>
                                <span class="nav__name">ACCOUNTING</span>
                            </a>
                        </div>

                        <div class="nav__dropdown">
                            <a href="#" class="nav__link">
                                <i class='bx bxs-bank nav__icon'></i>
                                <span class="nav__name">BOOKKEEPING</span>
                                <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                            </a>

                            <div class="nav__dropdown-collapse">
                                <div class="nav__dropdown-content">
                                    <a href="B-Cashbook-Entry.php" class="nav__dropdown-item">Cashbook Entry</a>
                                    <a href="B-Income-Statement.php" class="nav__dropdown-item">Income Statement</a>
                                    <a href="B-Cash-flow.php" class="nav__dropdown-item">Cash Flow</a>
                                </div>
                            </div>
                        </div>


                        <div class="nav__items">
                            <h3 class="nav__subtitle">EXTRAS</h3>
                            <a href="Profile.php" class="nav__link">
                                <i class='bx bx-wink-smile nav__icon' ></i>
                                <span class="nav__name">PROFILE</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="../logout.php" class="nav__link nav__logout">
                <i class='bx bx-log-out nav__icon' ></i>
                <span class="nav__name">LOG OUT</span>
            </a>
        </nav>
    </div>
