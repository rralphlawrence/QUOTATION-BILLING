<?php
        
     include "includes/header.php";
     include "includes/sidebar.php";
     include "includes/footer.php";

?>

        <html>
            <body onload="setDataOnSelection()">
                <main>
                    <section>
                    <div class="mis-logo">
                        <img src="./img/misoutlogo.png" alt="">
                        <h3 class="mis-title"> BILLING REPORT</h3>
                    </div>
                    <form action="../includes/dbprocess.php" Method="POST">
                    <div class="sorter">
                         <div class="select">
                             <select id="Month" name="month_bill">  
                                <option value="0" disabled selected>MONTH</option>
                               <option value="1">JANUARY</option>
                               <option value="2">FEBRUARY</option>
                               <option value="3">MARCH</option>
                               <option value="4">APRIL</option>
                               <option value="5">JUNE</option>
                               <option value="6">JULY</option>
                               <option value="7">AUGUST</option>
                               <option value="8">SEPTEMBER</option>
                               <option value="9">OCTOBER</option>
                               <option value="10">NOVEMBER</option>
                               <option value="11">DECEMBER</option>
                               </select>
                         </div>
                         <div class="select">
                             <select id="Year" name="year_bill">  
                             <?php
                                    $query = "SELECT DISTINCT YEAR(reportB_transaction_date) as year FROM `tblbreport` ORDER BY  reportB_transaction_date ASC";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                <option value="0" disabled selected>YEAR</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['year']; ?>"><?= $row['year']; ?></option>
                                <?php } ?> 
                               </select>
                         </div>

                         <button class="show-btn" type="submit" name="breport_show"> SHOW </button>
                      </div>
                      </form>



                      <?php       
                         
                         $month = $_SESSION['date_month_showbill'];
                         $year = $_SESSION['date_year_showbill'];

                        // $month = '4';
//$year = '2022';
                         
     
                         $query = "SELECT * FROM `tblbreport` WHERE YEAR(`reportB_transaction_date`) = '$year' AND  MONTH(`reportB_transaction_date`) = '$month' ORDER BY `reportB_transaction_date` ASC";    
                         $stmt = $conn->prepare($query);
                         $stmt-> execute();
                         $result = $stmt->get_result();  
                         ?>   

                      <div class="data-table">
                        <table class="table">
                            <thead>
                                <th>Date</th>
                                <th>Provider</th>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                            </thead>
                            <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td data-label="Date"><?= $row['reportB_transaction_date'] ?></td>
                                    <td data-label="Client Name"><?= $row['reportB_provider'] ?></td>
                                    <td data-label="Transaction ID"><?= $row['reportB_transaction_id'] ?></td>
                                    <td data-label="Amount"><strong>₱ </strong><?= number_format($row['reportB_client_payment']) ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                            <div class="Total-amount">

                            <?php
                                
                                $query = "SELECT SUM(reportB_client_payment) as TOtal_Amount FROM `tblbreport` WHERE YEAR(`reportB_transaction_date`) = '$year' AND  MONTH(`reportB_transaction_date`) = '$month' ORDER BY `reportB_transaction_date` ASC";    
                                $stmt = $conn->prepare($query);
                                $stmt-> execute();
                                $result = $stmt->get_result();  
                                ?>  
                                <table>
                                    <thead>
                                        <tr>
                                            <th>TOTAL AMOUNT</th>
                                          
                                          
                                        </tr>
                                  
                                        <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        
                                           <td><strong>₱ </strong><?= number_format($row['TOtal_Amount']) ?></td>
                                        </tr>
                                        <?php } ?>
                                        </thead>
                                </table>
                            </div>

                        
                    </div>

                  </section>
                </main>    
                <script>
                        

                        function setDataOnSelection(){
                            $('#Month').val("<?php echo $_SESSION['date_month_showbill']?>");
                            $('#Year').val("<?php echo $_SESSION['date_year_showbill']?>");
                           
                        }

                    </script>

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
            </body>
        </html>