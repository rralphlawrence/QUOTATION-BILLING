

 <?php
        
        include "includes/header.php";
        include "includes/sidebar.php";
        include "includes/footer.php";
   
   ?>
   
   
     <html>
       <body>
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
           
           <!--========== CONTENTS ==========-->
           <main>
               <section>
   
                       <div class="mis-logo">
                           <img src="./img/misoutlogo.png" alt="">
                           <h3 class="mis-title">ALWAYS THE FASTEST</h3>
                       </div>
   
                           <div class="main-content">
                               <div class="info-card">
                                   <div class="card">
                                       <div class="card-icon">
                                           <span>
                                               <i class="fas fa-user"></i>
                                           </span>
                                       </div>
                                       <?php
                            
                            $query = "SELECT COUNT(client_name) as total FROM `tblclient`";    
                            $stmt = $conn->prepare($query);
                            $stmt-> execute();
                            $result = $stmt->get_result();  
                           
              ?>
                                       <div class="card-detail">
                                           <h4>Total Client</h4>
                                           <?php while($row = $result->fetch_assoc()){ ?>
                                           <h2><?= $row['total'] ?></h2>
   <?php } ?> 
                                       </div>
                                      
                                   </div>
   
                                   <div class="card">
                                       <div class="card-icon">
                                           <span>
                                               <i class="fas fa-money-bill"></i>
                                           </span>
                                       </div>
                                       <?php
                            
                            $query = "SELECT SUM(accounting_total_profit) as total FROM `tblaccounting`";    
                            $stmt = $conn->prepare($query);
                            $stmt-> execute();
                            $result = $stmt->get_result();  
                           
              ?>
                                       <div class="card-detail">
                                           <h4>Total Income</h4>
                                           <?php while($row = $result->fetch_assoc()){ ?>
                                           <h2> ₱ <?= number_format($row['total']) ?></h2>
   <?php } ?> 
                                       </div>
                                      
                                   </div>
   
                                   <div class="card">
                                       <div class="card-icon">
                                           <span>
                                               <i class="icon fas fa-users"></i>
                                           </span>
                                       </div>
                                       <?php
                            
                            $query = "SELECT COUNT(supplier_name) as total FROM `tblsupplier`";    
                            $stmt = $conn->prepare($query);
                            $stmt-> execute();
                            $result = $stmt->get_result();  
                           
              ?>
                                       <div class="card-detail">
                                           <h4>Total Suppliers</h4>
                                           <?php while($row = $result->fetch_assoc()){ ?>
                                           <h2><?= $row['total'] ?></h2>
   <?php } ?> 
                                       </div>
                                       
                                   </div>
                               </div>
                           </div>
                          <div class="chart-grid">
                                <div id="LineChart" style="height: 310px;" ></div>
                         </div>
                  
               </section>
           </main>
   
   
   
           <?php 
   
               
               $income = array();
   
   
               for ($i=1; $i < 13; $i++) { 
                   # code...
                   $query = "SELECT SUM(accounting_total_profit) as total FROM `tblaccounting` WHERE accounting_month_number = $i";    
                   $stmt = $conn->prepare($query);
                   $stmt-> execute();
                   $result = $stmt->get_result();  
                   while($row = $result->fetch_assoc()){
   
                       $income[$i] = $row['total'];
                   }
   
                   if($income[$i] == ''){
                       $income[$i] = 0;
                   }
   
               }
   
               
   
           ?>
   
   
   <script>
   
   Morris.Bar({
     element: 'LineChart',
     barColors: ['#79717A'],
     data: [
       { y: 'JANUARY', a: <?= $income[1] ?>},
       { y: 'FEBRUARY', a: <?= $income[2] ?>},
       { y: 'MARCH', a: <?= $income[3] ?>},
       { y: 'APRIL', a: <?= $income[4] ?>},
       { y: 'MAY', a: <?= $income[5] ?> },
       { y: 'JUNE', a: <?= $income[6] ?>},
       { y: 'JULY', a: <?= $income[7] ?>},
       { y: 'AUGUST', a: <?= $income[8] ?> },
       { y: 'SEPTEMBER', a: <?= $income[9] ?>},
       { y: 'OCTOBER', a: <?= $income[10] ?> },
       { y: 'NOVEMBER', a: <?= $income[11] ?>},
       { y: 'DECEMBER', a: <?= $income[12] ?>}
     ],
     xkey: 'y',
     ykeys: ['a'],
     labels: ['₱ ']
   });
   </script>
           <!--========== MAIN JS ==========-->
         
         
          
       </body>
       </html>