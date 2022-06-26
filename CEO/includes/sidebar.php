<?php


 

?>
<div class="nav" id="navbar">
            <nav class="nav__container">
                <div>
                    <a href="#" class="nav__link nav__logo">
                       
                            <img class="profile-img" src="../CEO/img/profile-picture/<?php echo $_SESSION ['user_image']; ?>" width="50" height="50" alt="">
                      
                  
                        <span class="nav__logo-name"><?php echo $_SESSION ['user_realname']?></span>
                    
                    </a>
    
                    <div class="nav__list">
                        <div class="nav__items">
                            <h3 class="nav__subtitle">FEATURES</h3>
    
                            <a href="./dashboard.php" class="nav__link active">
                                <i class='bx bx-bar-chart nav__icon' ></i>
                                <span class="nav__name">DASHBOARD</span>
                            </a>

                            <a href="./account.php" class="nav__link active">
                                <i class='bx bx-pie-chart-alt-2 nav__icon' ></i>
                                <span class="nav__name">ACCOUNTS</span>
                            </a>
                            
                            <div class="nav__dropdown">
                                <a href="#" class="nav__link">
                                    <i class='bx bxs-package nav__icon' ></i>
                                    <span class="nav__name">BILLING</span>
                                    <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                                </a>

                                <div class="nav__dropdown-collapse">
                                    <div class="nav__dropdown-content">
                                        <a href="./billing-approval.php" class="nav__dropdown-item">For Approval</a>
                                        <a href="./billing-check.php" class="nav__dropdown-item">Check OR</a>
                                 
                                    </div>
                                </div>
                            </div>

                            <div class="nav__dropdown">
                                <a href="#" class="nav__link">
                                    <i class='bx bxs-receipt nav__icon' ></i>
                                    <span class="nav__name">QUOTATION</span>
                                    <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                                </a>

                                <div class="nav__dropdown-collapse">
                                    <div class="nav__dropdown-content">
                                        <a href="./quotation-approval.php" class="nav__dropdown-item">For Approval</a>
                                        <a href="./quotation-check.php" class="nav__dropdown-item">Check OR</a>
                                        
                                    </div>
                                </div>
                            </div>

                          
                            <a href="./history.php" class="nav__link active">
                            <i class='bx bx-history nav__icon'></i>
                                <span class="nav__name">HISTORY</span>
                            </a>
                        
                        </div>
    
                        <div class="nav__items">
                            <h3 class="nav__subtitle">EXTRAS</h3>
     
                           

                            <a href="./profile.php" class="nav__link">
                                <i class='bx bx-wink-smile nav__icon' ></i>
                                <span class="nav__name">PROFILE</span>
                            </a>
                            
                        </div>
                    </div>
                </div>

                <a href="../logout.php" class="nav__link nav__logout">
                    <i class='bx bx-log-out nav__icon' ></i>
                    <span class="nav__name">LOG OUT</span>
                </a>
            </nav>
        </div>

       
