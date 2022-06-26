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
                        <div class="hero-button">
                            <button class="add-supplier main-btn" id="newRecord" data-modal-target="#modal">ADD RECORD</button>
                        </div>
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
                                <th>Action</th>
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
                                    <td data-label="Action">
                                      
                            <button class="btn-edit">
                                <a href="javascripit:void(0)" class="button editData" data-modal-target="#modal">
                                    EDIT
                                </a>
                            </button> 

                                <button class="btn-delete">
                                <a href="javascripit:void(0)" class="button deleteData">DELETE </a>
                                </button>                              

                                    </td>
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
                    <div class="modal" id="modal" style="height:610px">
                        <div class="modal-header">
                           
                          <button data-close-button class="close-button">&times;</button>
                        </div>
                        <div class="modal-logo">
                            <img src="img/misoutlogo.png" alt="misout logo" width="60px" height="60px">
                        </div>
                        <div class="modal-body">
                            <div class="l-form">
                                <form action="../includes/dbprocess.php" class="form" method="POST">

                                <div class="form__div">
                                  
                                        <div class="select">
                                            <select name="ac_month"  id="ac_month" required>  
                                              <option hidden value="wala">SELECT MONTH</option>
                                              <option value="1">JANUARY</option>
                                              <option value="2">FEBRUARY</option>
                                              <option value="3">MARCH</option>
                                              <option value="4">APRIL</option>
                                              <option value="5">MAY</option>
                                              <option value="6">JUNE</option>
                                              <option value="7">JULY</option>
                                              <option value="8">AUGUST</option>
                                              <option value="9">SEPTEMBER</option>
                                              <option value="10">OCTOBER</option>
                                              <option value="11">NOVEMBER</option>
                                              <option value="12">DECEMBER</option>
                                            </select>
                                          </div>
                                    </div>

                                    <div class="form__div">
                                        <input type="text" name="ac_year" id="ac_year" class="form__input" placeholder=" " onkeypress="return onlyNumberKey(event)" placeholder="Put year in this format : '2021'" minlength="4" maxlength="4" required >
                                        <label for="" class="form__label">YEAR</label>
                                    </div>

                                
                                <div class="form__div">
                                        <input type="text" name="ac_number" id="ac_number" class="form__input" placeholder=" " required>
                                        <label for="" class="form__label">ACCOUNT NUMBER</label>
                                    </div>


                                    <div class="form__div">
                                        <input type="text" name="ac_mbill" id="ac_mbill" class="form__input" placeholder=" " required>
                                        <label for="" class="form__label">MONTHLY BILL</label>
                                    </div>

                                    
                                    <div class="form__div">
                                        <input type="text" name="ac_notes" id="ac_notes" class="form__input" placeholder=" " required>
                                        <label for="" class="form__label">NOTES</label>
                                    </div>
                                    
                                    <div class="form__div">
                                        <input type="text" name="ac_cpayment" id="ac_cpayment" class="form__input" placeholder=" " onkeypress="return onlyNumberKey(event)" required>
                                        <label for="" class="form__label">CLIENT PAYMENT</label>
                                    </div>
                    
                                   
                                    <div class="form__div">
                                        <input type="text" name="ac_mpayment"  id="ac_mpayment" class="form__input" placeholder=" " onkeypress="return onlyNumberKey(event)" required>
                                        <label for="" class="form__label">PAID BY MISOUT</label>
                                    </div>

                                    
                                    <div class="form__div">
                                        <input type="text" name="ac_commission" id="ac_commission" class="form__input" placeholder=" " onkeypress="return onlyNumberKey(event)" required>
                                        <label for="" class="form__label">COMMISION</label>
                                    </div>
                                    <input type="hidden" name="ac_id" id="ac_id" class="form__input" placeholder=" ">
                                    <input type="hidden" name="ac_provider" id="ac_provider" class="form__input" placeholder=" ">
                                    <input type="submit" class="form__button" name="ac_save" value="SAVE">
                                </form>
                            </div>
                        </div>
                      </div>
                      <div id="overlay"></div>



                      


                  </section>
                </main>    

            </body>
        </html>

<script>

function onlyNumberKey(evt) {
          
          // Only ASCII character in that range allowed
          var ASCIICode = (evt.which) ? evt.which : evt.keyCode
          if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)){
             if(ASCIICode == 46){
                     return true;
                 }
                 return false;
          }
                
         return true;

     
 }


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

                          
                          
                          
                          $('.deleteData').on('click', function(e){

e.preventDefault();

$tr = $(this).closest('tr');
var data = $tr.children("td").map(function(){
return $(this).text();
}).get();

var id = data[0];




swal({
        title: "Are you sure to delete this file?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
.then((willDelete) => {
    
    if (willDelete) {

        $.ajax({
            type: "POST",
            url: "../includes/dbprocess.php", 
            data: {
                "delete_btn_account":1,
                "delete_id_supplier": id,
            },
        success: function(result){
            swal({
                title: "Successfully File Deleted!",
                icon: "success",
            }).then((result) => {
                location.reload();
            });
        }
    });

    }   

});
});


                          $('#newRecord').on('click', function(e){

                          e.preventDefault();

        var ac_id = '';
        var ac_month = 'wala';
        var ac_year = '';
        var ac_provider =  $('#provider').val();
        var ac_number = '';
        var ac_commission = '';
        var ac_mbill = '';
        var ac_notes = '';
        var ac_mpayment = '';
        var ac_cpayment = '';

    $('#ac_id').val(ac_id);
    $('#ac_month').val(ac_month);
    $('#ac_year').val(ac_year);
    $('#ac_number').val(ac_number);
    $('#ac_commission').val(ac_commission);
    $('#ac_mbill').val(ac_mbill);
    $('#ac_notes').val(ac_notes);
    $('#ac_mpayment').val(ac_mpayment);
    $('#ac_cpayment').val(ac_cpayment);
    $('#ac_provider').val(ac_provider);
   

});



$('.button, .editData').on('click', function(e){

e.preventDefault();

 $tr = $(this).closest('tr');
 var data = $tr.children("td").map(function(){
    return $(this).text();
}).get();


var ac_id = data[0];
var ac_month = data[1];
var ac_year = $('#account_year').val();
var ac_number = $('#account_no').val();
var ac_commission = data[9].substring(2).replace(/,/g, "");
var ac_mbill = data[3].substring(2).replace(/,/g, "");
var ac_notes = data[4];
var ac_provider =  $('#provider').val();
var ac_mpayment = data[6].substring(2).replace(/,/g, "");
var ac_cpayment = data[5].substring(2).replace(/,/g, "");



    $('#ac_id').val(ac_id);
    $('#ac_month').val(ac_month);
    $('#ac_year').val(ac_year);
    $('#ac_number').val(ac_number);
    $('#ac_commission').val(ac_commission);
    $('#ac_mbill').val(ac_mbill);
    $('#ac_notes').val(ac_notes);
    $('#ac_mpayment').val(ac_mpayment);
    $('#ac_cpayment').val(ac_cpayment);
    $('#ac_provider').val(ac_provider);

});






















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
       