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
                        <h3 class="mis-title"> QUOTATION REPORT</h3>
                    </div>

                    <form action="../includes/dbprocess.php" Method="POST">
                    <div class="sorter">
                    <div class="select">
                             <select id="Month" name="month">  
                             <option value="0" disabled selected>MONTH</option>  
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                               </select>
                         </div>
                         <div class="select">
                         <?php
                                    $query = "SELECT DISTINCT YEAR(reportQ_transaction_date) as year FROM `tblqreport` ORDER BY  reportQ_transaction_date ASC";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                <select id="Year" name="year">    
                                <option value="0" disabled selected>YEAR</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['year']; ?>"><?= $row['year']; ?></option>
                                <?php } ?>  
                                </select>
                         </div>

                         <button class="show-btn" type="submit" name="qreport_show"> SHOW </button>
                      </div>
                                </form>

                      <div class="data-table">


                      <?php       
                         
                         $month = $_SESSION['date_month_show'];
                         $year = $_SESSION['date_year_show'];

                        // $month = '4';
//$year = '2022';
                         
     
                         $query = "SELECT * FROM `tblqreport` WHERE YEAR(`reportQ_transaction_date`) = '$year' AND  MONTH(`reportQ_transaction_date`) = '$month' ORDER BY `reportQ_transaction_date` ASC";    
                         $stmt = $conn->prepare($query);
                         $stmt-> execute();
                         $result = $stmt->get_result();  
                         ?>   
                        <table class="table">
                            <thead>
                                <th>Date</th>
                                <th>Client Name</th>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                               
                            </thead>
                            
                            <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td data-label="Date"><?= $row['reportQ_transaction_date'] ?></td>
                                    <td data-label="Client Name"><?= $row['reportQ_client_name'] ?></td>
                                    <td data-label="Transaction ID"><?= $row['reportQ_transaction_id'] ?></td>
                                    <td data-label="Amount"><strong>₱ </strong><?= number_format($row['reportQ_amount']) ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                            <div class="Total-amount">
                                <?php
                                
                         $query = "SELECT SUM(reportQ_amount) as TOtal_Amount FROM `tblqreport` WHERE YEAR(`reportQ_transaction_date`) = '$year' AND  MONTH(`reportQ_transaction_date`) = '$month' ORDER BY `reportQ_transaction_date` ASC";    
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
                            $('#Month').val("<?php echo $_SESSION['date_month_show']?>");
                            $('#Year').val("<?php echo $_SESSION['date_year_show']?>");
                           
                        }

                    </script>
            </body>
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
           
        </html>