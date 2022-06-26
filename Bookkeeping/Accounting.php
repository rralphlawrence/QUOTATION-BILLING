<?php
        
     include "includes/header.php";
     include "includes/sidebar.php";
     include "includes/footer.php";

?>

        <html>
            <body onload="setDataOnSelection()">
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
                <main>
                    <section>
                    <div class="hero-section">
                    <div class="select">
                     <?php
                                    $query = "SELECT DISTINCT `supplier_name` FROM `tblsupplier` WHERE `supplier_type` = 'Billing'";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                <select id="provider" name="supplier" onchange="changetable()">    
                                <option value="wala" disabled selected>SELECT PROVIDER</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['supplier_name']; ?>"><?= strtoupper($row['supplier_name']); ?></option>
                                <?php } ?>  
                                </select>
                         </div>
                         <?php 

                                                  if($_SESSION['provider_selected'] != 'wala' ){
                                    
                                                  
                         
                         ?>
                        <?php } ?>
</div>


<?php 
                                      $selection = $_SESSION['provider_selected'];
                                      $accountno_selected = $_SESSION['accountno_selected'];
                                      $accountyear_selected = $_SESSION['accountyear_selected'];

                                  if($selection == 'wala' ){

                                  }else{

                                  


?>



                   <!-- start na dito yung pagsort -->
                    <div class="sorter">
                    <div class="select">
                    <?php
                    
                                    $query = "SELECT DISTINCT `accounting_account_number` FROM `tblaccounting` WHERE `accounting_supplier` = '$selection'";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                <select id="account_no" name="account_no">    
                                <option value="wala" disabled selected>SELECT ACCOUNT NO</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['accounting_account_number']; ?>"><?= strtoupper($row['accounting_account_number']); ?></option>
                                <?php } ?>  
                                </select>
                         </div>

                         <div class="select">
                               <?php
                    
                                    $query = "SELECT DISTINCT `accounting_year` FROM `tblaccounting` WHERE `accounting_supplier` = '$selection'";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                <select id="account_year" name="account_year">    
                                <option value="wala" disabled selected>SELECT YEAR</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['accounting_year']; ?>"><?= strtoupper($row['accounting_year']); ?></option>
                                <?php } ?>  
                                </select>
                         </div>

                         <div class="hero-button">
                            <button class="add-supplier main-btn" onclick="changetables()">SHOW</button>
                        </div>

                      </div>

                      <?php
                         
                          $query = "SELECT * FROM `tblaccounting` WHERE `accounting_supplier`='$selection' AND `accounting_account_number` = '$accountno_selected' AND `accounting_year` = '$accountyear_selected' ORDER BY `accounting_month_number` ASC";    
                          $stmt = $conn->prepare($query);
                          $stmt-> execute();
                          $result = $stmt->get_result();  
                         
            ?>


                      <div class="data-table">
                        <table class="table">
                            <thead>
                                <th hidden>ID</th>
                                <th hidden>Month No</th>
                                <th>Month</th>
                                <th>Monthly Bill</th>
                                <th>Notes</th>
                                <th>Client Payment</th>
                                <th>Paid by MISOUT</th>
                                <th>Value Added Tax</th>
                                <th>Gross Income</th>
                                <th>Commission</th>
                                <th>Net Profit</th>
                            </thead>
                            <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td data-label="ID" hidden ><?= $row['accounting_id']?></td>
                                    <td data-label="MonthNo" hidden><?= $row['accounting_month_number']?></td>
                                    <td data-label="Month"><?= $row['accounting_month']?></td>
                                    <td data-label="Monthly Bill"><strong>₱ </strong><?= number_format($row['accounting_monthly_bill']) ?></td>
                                    <td data-label="Notes"><?= $row['accounting_notes']?></td>
                                    <td data-label="Client Payment"><strong>₱ </strong><?= number_format($row['accounting_payment_client']) ?></td>
                                    <td data-label="Paid by MISOUT"><strong>₱ </strong><?= number_format($row['accounting_paid_by_misout']) ?></td>
                                    <td data-label="VAT"><strong>₱ </strong><?= number_format($row['accounting_vat']) ?></strong></td>
                                    <td data-label="Gross Income"><strong>₱ </strong><?= number_format($row['accounting_gross_income']) ?></td>
                                    <td data-label="Commission"><strong>₱ </strong><?= number_format($row['accounting_commission']) ?></td>
                                    <td data-label="Profit"><strong>₱ </strong><?= number_format($row['accounting_total_profit']) ?></td>
                                </tr>
                               <?php } ?>
                            </tbody>
                        </table>
                            <div class="Total-amount">
                            <?php
                         
                         $query = "SELECT SUM(accounting_total_profit) as total FROM `tblaccounting` WHERE `accounting_supplier`='$selection' AND `accounting_account_number` = '$accountno_selected' AND `accounting_year` = '$accountyear_selected' ORDER BY `accounting_month_number` ASC";    
                         $stmt = $conn->prepare($query);
                         $stmt-> execute();
                         $result = $stmt->get_result();  
                        
           ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>TOTAL NET PROFIT</th>
                                        </tr>
                                        <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>                               
                                           <td><strong>₱ </strong><?= number_format($row['total'])?></td>
                                           
                                        </tr>
                                        <?php }?>
                                        </thead>
                                </table>
                            </div>    
                    </div>

                    <?php } ?>
                    



                      


                  </section>
                </main>    

            </body>
        </html>

<script>




                        function setDataOnSelection(){
                            $('#provider').val("<?php echo $_SESSION['provider_selected']?>");
                            $('#account_no').val("<?php echo $_SESSION['accountno_selected']?>");
                            $('#account_year').val("<?php echo $_SESSION['accountyear_selected']?>");
                        }


                        function changetable(){

                          var provider_selected =   $('#provider').val();

                    $.ajax({
                    type: "POST",
                    url: "../includes/dbprocess.php", 
                    data: {
                        "show_accounts":1,
                        "provider_selected": provider_selected,
                    },
                    success: function(result){
                        location.reload();
                    }

                     });
                          }



                          function changetables(){

                          var account_no =   $('#account_no').val();
                          var account_year =   $('#account_year').val();

                    $.ajax({
                    type: "POST",
                    url: "../includes/dbprocess.php", 
                    data: {
                        "shows_accounts":1,
                        "account_no": account_no,
                        "account_year": account_year,
                    },
                    success: function(result){
                        location.reload();
                    }

                     });
                          }

                          
                          
                         














                  const openModalButtons = document.querySelectorAll('[data-modal-target]')
      const closeModalButtons = document.querySelectorAll('[data-close-button]')
      const overlay = document.getElementById('overlay')

      openModalButtons.forEach(button => {
        button.addEventListener('click', () => {
          const modal = document.querySelector(button.dataset.modalTarget)
          openModal(modal)
        })
      })

      overlay.addEventListener('click', () => {
        const modals = document.querySelectorAll('.modal.active')
        modals.forEach(modal => {
          closeModal(modal)
        })
      })

      closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
          const modal = button.closest('.modal')
          closeModal(modal)
        })
      })

      function openModal(modal) {
        if (modal == null) return
        modal.classList.add('active')
        overlay.classList.add('active')
      }

      function closeModal(modal) {
        if (modal == null) return
        modal.classList.remove('active')
        overlay.classList.remove('active')
      }
  
</script>
       