<?php


 

?>
<div class="nav" id="navbar">
            <nav class="nav__container">
                <div>
                    <a href="#" class="nav__link nav__logo">
                       
                    <img class="profile-img" src= "../Admin/img/profile-picture/<?= $_SESSION ['user_image']; ?>" width="50" height="50" alt="">
                      
                  
                        <span class="nav__logo-name"><?php echo $_SESSION ['user_realname']?></span>
                    
                    </a>
    
                    <div class="nav__list">
                        <div class="nav__items">
                            <h3 class="nav__subtitle">FEATURES</h3>
    
                            <a href="./dashboard.php" class="nav__link active">
                                <i class='bx bx-bar-chart nav__icon' ></i>
                                <span class="nav__name">DASHBOARD</span>
                            </a>
                            
                            <div class="nav__dropdown">
                                <a href="#" class="nav__link">
                                    <i class='bx bxs-package nav__icon' ></i>
                                    <span class="nav__name">CLIENT | SUPPLIER</span>
                                    <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                                </a>

                                <div class="nav__dropdown-collapse">
                                    <div class="nav__dropdown-content">
                                        <a href="./supplier.php" class="nav__dropdown-item">Supplier</a>
                                        <a href="./product.php" class="nav__dropdown-item">Product</a>
                                        <a href="./client.php" class="nav__dropdown-item">Client</a>
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
                                        <a href="./Quotation.php" class="nav__dropdown-item">Create new</a>
                                        <a href="./onprogress.php" class="nav__dropdown-item">On progress</a>
                                        <a href="./report.php" class="nav__dropdown-item">Report</a>
                                        <a href="./record.php" class="nav__dropdown-item">Records</a>
                                    </div>
                                </div>
                            </div>

                            <div class="nav__dropdown">
                                <a href="#" class="nav__link">
                                    <i class='bx bx-money-withdraw nav__icon' ></i>
                                    <span class="nav__name">BILLING</span>
                                    <i class='bx bx-chevron-down nav__icon nav__dropdown-icon'></i>
                                </a>

                                <div class="nav__dropdown-collapse">
                                    <div class="nav__dropdown-content">
                                        <a href="./create-billing.php" class="nav__dropdown-item">Create new</a>
                                        <a href="./onprogress-billing.php" class="nav__dropdown-item">On progress</a>
                                        <a href="./report-billing.php" class="nav__dropdown-item">Report</a>
                                        <a href="./billing-record.php" class="nav__dropdown-item">Records</a>
                                    </div>
                                </div>
                            </div>


                            <a href="./accounting.php" class="nav__link active">
                                <i class='bx bx-pie-chart-alt-2 nav__icon' ></i>
                                <span class="nav__name">ACCOUNTING</span>
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

       
