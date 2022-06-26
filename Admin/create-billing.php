<?php
        
        include "includes/header.php";
        include "includes/sidebar.php";
        include "includes/footer.php";
?>
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

       <div class="hero-section">
                     <div class="select">
                     <?php
                                    $query = "SELECT DISTINCT `supplier_name` FROM `tblsupplier` WHERE `supplier_type` = 'Billing'";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                <select id="supplier" name="supplier" onchange="changetable()">    
                                <option value="wala" disabled selected>PROVIDER</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['supplier_name']; ?>"><?= strtoupper($row['supplier_name']); ?></option>
                                <?php } ?>  
                                </select>
                         </div>
<?php if($_SESSION['selected_provider'] == "wala"){
    
}else{


?>

<div class="hero-button">
                            <button class="add-supplier main-btn" onclick="setPro()">GENERATE BILL</button>
                        </div>
</div>

<button class="input-billing "data-modal-target="#modal" id="input-data"> <i class='bx bx-add-to-queue'></i>  INPUT BILLING</button>


                               <div class="data-table">
                                        <div class="monthly-label">
                                            <h3><?php echo $_SESSION['selected_provider']?> MONTHLY BILLING</h3>
                                            </div>




                                    <?php

                                    $prov = $_SESSION['selected_provider'];
                                    

                                        
       $sqlforNoAccount = "SELECT DISTINCT `gb_client_group` FROM `tblgeneratebill` WHERE `gb_provider` = '$prov'";
       $sqlrun = mysqli_query($conn, $sqlforNoAccount);

                if(mysqli_num_rows($sqlrun)>0){

                                     $sqlforNoAccount = "SELECT DISTINCT `gb_client_group` FROM `tblgeneratebill` WHERE `gb_provider` = '$prov'";
                                     $stmt = $conn->prepare($sqlforNoAccount);
                                     $stmt->execute();
                                     $result = $stmt->get_result();
                                     $group[] = array(); 
                                     $a = 0;
                                     while ($row = $result->fetch_assoc()) {
                                             $group[$a] = $row['gb_client_group'];
                                             $a++;
                                         }
                                 
                                
                                         $total_no = count($group);
                                
                                
                                    for ($i=0; $i < $total_no; $i++) { 
                                    
                                    
                                    ?>

                                            <div class="monthly-label">
                                            <h4><?= $group[$i]?></h4>
                                            </div>
                                       

                                            <?php
                         $query = "SELECT * FROM `tblgeneratebill` WHERE `gb_client_group` = '$group[$i]' AND gb_provider  = '$prov'";    
                         $stmt = $conn->prepare($query);
                         $stmt->execute();
                         $result = $stmt->get_result();

                         $no = 1;
            ?>       

                        <table class="table">
                            <thead>
                                
                                <th hidden>ID</th>
                                <th>No</th>
                                <th>Account No</th>
                                <th>Client Name</th>
                                <th>Service Promo</th>
                                <th>Billing Period</th>
                                <th>Billing Price</th>
                                <th>Client Payment</th>
                                <th>Action</th>
                            </thead>
                            <tbody>

                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td data-label="ID" hidden><?=$row['gb_id']?></td>
                                    <td data-label="No"><?=$no ?></td>
                                    <td data-label="Account No"><?=$row['gb_account_number']?></td>
                                    <td data-label="Client Name"><?=$row['gb_client_name']?></td>
                                    <td data-label="Service Promo"><?=$row['gb_service_promo']?></td>
                                    <td data-label="Billing Period"><?=$row['gb_billing_period']?></td>
                                    <td data-label="Billing Price"><strong>₱ <?= number_format($row['gb_billing_price']) ?></strong></td>
                                    <td data-label="Client Payment"><strong>₱ <?= number_format($row['gb_client_payment']) ?></strong></td>
                                    <td data-label="Action">
                                    <button class="btn-edit"><a href="javascripit:void(0)"  data-modal-target="#modal" class="button editData">EDIT</a></button>
                                    <button class="btn-delete"><a href="javascripit:void(0)" class="delete-item-confirm">REMOVE</a></button>
                                    
                                    </td>
                                    
                                </tr>
                            <?php $no++; } ?>
                            </tbody>
                        </table>
                        <div class="Total-amount">
                                <table>
                                <?php
                         $query = "SELECT SUM(gb_client_payment) as total_payment, SUM(gb_billing_price) as total_price FROM `tblgeneratebill` WHERE `gb_client_group` = '$group[$i]' AND gb_provider  = '$prov'";    
                         $stmt = $conn->prepare($query);
                         $stmt->execute();
                         $result = $stmt->get_result();

            ?>       
                                    <thead>
                                        <tr>
                                            
                                            <th>TOTAL BILLING PRICE</th>
                                            <th>TOTAL CLIENTS PAYMENT</th>
                                        </tr>
                                  
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                           <td><strong>₱ <?= number_format($row['total_price'])?> </strong></td>
                                           <td><strong>₱ <?= number_format($row['total_payment'])?> </strong></td>
                                        </tr>
                                        <?php }?>
                                        </thead>
                                </table>
                            </div>

                            <?php  }      ?>  
                    </div>
<?php } }?>
                    <div class="modal-bill" id="modal-bag">
        <form action="../includes/dbprocess.php" class="modal-content" method="post">
            <div class="imgcontainer">
                <span class="close-btn">&times;</span>
                <img src="img/misoutlogo.png" width="50px" alt="misoutimage" >
              </div>
              <div class="form__div">
                  <input type="date" class="form__input" name="selected_date" placeholder=" " required>
                         <label for="" class="form__label">DATE</label>
             </div>
             <input type="text" name="client_provider" id="client_provider" placeholder=" " hidden>
             <input type="submit" class="form__button" name="btn_generate_bill" value="SAVE">
             
        </form>
    </div>

                    <div class="modal" id="modal" style="height: 560px;">
                        <div class="modal-header">
                           
                          <button data-close-button class="close-button">&times;</button>
                        </div>
                        <div class="modal-logo">
                            <img src="img/misoutlogo.png" alt="misout logo" width="60px" height="60px">
                        </div>
                        <div class="modal-body">
                            <div class="l-form">


                                <form action="../includes/dbprocess.php" class="form" method="POST" enctype="multipart/form-data">
                                   
                    
                                <div class="form__div">
                                    <div class="select">
                                    <?php
                                    $query = "SELECT DISTINCT client_name FROM `tblclient` WHERE `client_type` = 'Billing' ORDER BY `client_name` ASC";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                <select name="client_name" id="client_name" required>    
                                <option value="wala" disabled selected>CLIENT NAME</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['client_name']; ?>"><?= strtoupper($row['client_name']); ?></option>
                                <?php } ?>  
                                </select>
                                    </div>
                                </div>
                                    <div class="form__div">
                                        <input type="text" class="form__input" name="client_accountnumber" id="client_accountnumber" placeholder=" " required>
                                        <label for="" class="form__label">ACCOUNT | MOBILE NUMBER</label>
                                    </div>

                                    <div class="form__div">
                                        <input type="text" class="form__input" name="client_loadpromo" id="client_loadpromo" placeholder=" " required> 
                                        <label for="" class="form__label">SERVICE | LOAD PROMO</label>
                                    </div>
                                    
                                    <div class="form__div">
                                        <input type="text" class="form__input" name="client_billperiod" id="client_billperiod" placeholder=" " required>
                                        <label for="" class="form__label">BILLING MONTH | PERIOD</label>
                                    </div>


                                    <div class="form__div">
                                        <input type="text" class="form__input" name="client_billamount" id="client_billamount" placeholder=" " onkeypress="return onlyNumberKey(event)" required>
                                        <label for="" class="form__label">BILLING AMOUNT</label>
                                    </div>

                                    <div class="form__div">
                                        <input type="text" name="client_payment" id="client_payment" class="form__input" placeholder=" " onkeypress="return onlyNumberKey(event)" required>
                                        <label for="" class="form__label">CLIENT PAYMENT</label>
                                    </div>
                                    

                                    <div class="form__div">
                                        <input type="file" id="file" hidden="hidden" name="client_receipt" />
                                                <button type="button" id="custom-button">UPLOAD RECEIPT</button>
                                                <span id="custom-text">No file chosen, yet.</span>
                                    </div>

                                    

                                    <input type="text" name="itemID" id="itemID" placeholder=" " hidden>
                                    <input type="text" name="client_provide" id="client_provide" placeholder=" " hidden>
                                    <input type="submit" class="form__button" value="SAVE" name="generate_bill">
                                </form>
                            </div>
                        </div>
                      </div>
                      <div id="overlay"></div>
                     
                      </body>


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


function setPro(){

    var id = $('#supplier').val();
   $('#client_provider').val(id);

}


function changetable(){

var supplier_selected =   $('#supplier').val();

$.ajax({
                    type: "POST",
                    url: "../includes/dbprocess.php", 
                    data: {
                        "show_bill":1,
                        "selected_provider": supplier_selected,
                    },
                    success: function(result){
                        location.reload();
                }

            });

}


$('#input-data').on('click', function(e){
    var id = $('#supplier').val();
   $('#client_provide').val(id);
   $('#itemID').val('new');

});


function setDataOnSelection(){
                            $('#supplier').val("<?php echo $_SESSION['selected_provider']?>");
                           
                        }


                        $('.delete-item-confirm').on('click', function(e){

e.preventDefault();

$tr = $(this).closest('tr');
var data = $tr.children("td").map(function(){
    return $(this).text();
}).get();

var deleteid = data[0];


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
        "delete_record_gbill":1,
        "deletefiles_id_confirm": deleteid,
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




$('.button, .editData').on('click', function(e){

e.preventDefault();

 $tr = $(this).closest('tr');
 var data = $tr.children("td").map(function(){
    return $(this).text();
}).get();

var id = data[0];
var account = data[2];
var client = data[3];
var service = data[4];
var billing_period = data[5];
var billing_amount = data[6].substring(2).replace(/,/g, "");
var client_payment = data[7].substring(2).replace(/,/g, "");
var prov = $('#supplier').val();

    $('#client_name').val(client);
    $('#client_accountnumber').val(account);
    $('#client_loadpromo').val(service);
    $('#client_billperiod').val(billing_period);
    $('#client_billamount').val(billing_amount);
    $('#client_payment').val(client_payment);
    $('#itemID').val(id);
    $('#client_provide').val(prov);

});


</script>

                      
                      <script>
            const realFileBtn = document.getElementById("file");
            const customBtn = document.getElementById("custom-button");
            const customTxt = document.getElementById("custom-text");

customBtn.addEventListener("click", function() {
  realFileBtn.click();
});

realFileBtn.addEventListener("change", function() {
  if (realFileBtn.value) {
    customTxt.innerHTML = realFileBtn.value.match( /[\/\\]([\w\d\s\.\-\(\)]+)$/)[1];
  } else {
    customTxt.innerHTML = "No file chosen, yet.";
  }
});

        </script>
            <script>
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
      
      <script>const modal = document.getElementById('modal-bag');
            const openBtn = document.querySelector('.main-btn');
            const closeBtn = document.querySelector('.close-btn');
            // click events
            openBtn.addEventListener('click', () => {
                modal.style.display = 'block';
            });

            closeBtn.addEventListener('click', () =>{
                modal.style.display = 'none';
            });

            window.addEventListener('click', (e) =>{
                if(e.target === modal){
                    modal.style.display = 'none';
                }
            });</script>