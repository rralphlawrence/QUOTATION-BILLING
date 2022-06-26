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
                           <div class="mis-logo">
                        <img src="./img/misoutlogo.png" alt="">
                        <h3 class="mis-title">CHECK RECEIPT</h3>
                    </div>

                            <div class="button-div">
                            <div class="sorter">
                  
                            <div class="select">
                                <?php
                                    $query = "SELECT a.onprogressB_transaction_id as transaction_id FROM tblbonprogress a, tblofficialreceipt b WHERE `onprogressB_status` = 'VERIFYING OR' AND a.onprogressB_transaction_id = b.or_transaction_id";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                    <select select id="transaction_id" name="transaction_id" onchange="changetable()" required> 
                                    <option value="wala" disabled selected>TRANSACTION ID</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['transaction_id']; ?>"><?= $row['transaction_id'] ?></option>
                                <?php } ?>  
                                        </select>
                                </div>

                                <button class="Add-account main-btn"  onclick="verify()">VERIFY O.R</button>
                                <button class="Add-account main-btn" style="color:white; background-color: red;" onclick="deny()">DENY O.R</button>
                            </div>
                                 
                                 
                            </div>

                            <?php 

$t_id = $_SESSION['transaction_idB1'];
         

if($t_id == 'wala'){

}else{


$query = "SELECT or_image FROM `tblofficialreceipt` WHERE `or_transaction_id` = '$t_id'";    
$stmt = $conn->prepare($query);
$stmt-> execute();
$result = $stmt->get_result();  






?>
                  <div class="image-container">
                  <?php while ($row = $result->fetch_assoc()) { ?>   
                        <div class="image-viewer">
                                <img src="../Admin/img/or/<?= $row['or_image'] ?>" alt="">
                        </div>

                        <div class="modal-image">
                                <span>&times;</span>
                                <img alt="">
                        </div>
                <?php }?>   
                 </div>
<?php
                 $query = "SELECT SUM(cb_billing_price) as total_amount, cb_provider FROM `tblcreatedbill` WHERE `cb_transaction_id` = '$t_id'";    
           $stmt = $conn->prepare($query);
           $stmt-> execute();
           $result = $stmt->get_result();  


?>
                
           <?php while ($row = $result->fetch_assoc()){ $prov = $row['cb_provider']?>
        <h1 style="text-align: center;"><strong>GRAND TOTAL: ₱ </strong><?= number_format($row['total_amount']) ?></h1>
                                

<?php } ?>



<div class="data-table">
                                        <div class="monthly-label">
                                            <h3><?php echo $prov?> MONTHLY BILLING</h3>
                                            </div>




                                    <?php

                                    
                                    

                                        
       $sqlforNoAccount = "SELECT DISTINCT `cb_client_group` FROM `tblcreatedbill` WHERE `cb_provider` = '$prov' AND cb_transaction_id  = '$t_id'";
       $sqlrun = mysqli_query($conn, $sqlforNoAccount);

                if(mysqli_num_rows($sqlrun)>0){

                                     $sqlforNoAccount = "SELECT DISTINCT `cb_client_group` FROM `tblcreatedbill` WHERE `cb_provider` = '$prov' AND cb_transaction_id  = '$t_id'";
                                     $stmt = $conn->prepare($sqlforNoAccount);
                                     $stmt->execute();
                                     $result = $stmt->get_result();
                                     $group[] = array(); 
                                     $a = 0;
                                     while ($row = $result->fetch_assoc()) {
                                             $group[$a] = $row['cb_client_group'];
                                             $a++;
                                         }
                                 
                                
                                         $total_no = count($group);
                                
                                
                                    for ($i=0; $i < $total_no; $i++) { 
                                    
                                    
                                    ?>

                                            <div class="monthly-label">
                                            <h4><?= $group[$i]?></h4>
                                            </div>
                                       

                                            <?php
                         $query = "SELECT * FROM `tblcreatedbill` WHERE `cb_client_group` = '$group[$i]' AND cb_provider  = '$prov' AND cb_transaction_id  = '$t_id'";    
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
                            </thead>
                            <tbody>

                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                <td data-label="ID" hidden><?=$row['cb_id']?></td>
                                    <td data-label="No"><?=$no ?></td>
                                    <td data-label="Account No"><?=$row['cb_account_number']?></td>
                                    <td data-label="Client Name"><?=$row['cb_client_name']?></td>
                                    <td data-label="Service Promo"><?=$row['cb_service_promo']?></td>
                                    <td data-label="Billing Period"><?=$row['cb_billing_period']?></td>
                                    <td data-label="Billing Price"><strong>₱ <?= number_format($row['cb_billing_price']) ?></strong></td>
                                    <td data-label="Client Payment"><strong>₱ <?= number_format($row['cb_client_payment']) ?></strong></td>
                                  
                                    
                                </tr>
                            <?php $no++; } ?>
                            </tbody>
                        </table>
                        <div class="Total-amount">
                                <table>
                                <?php
                         $query = "SELECT SUM(cb_client_payment) as total_payment, SUM(cb_billing_price) as total_price FROM `tblcreatedbill` WHERE `cb_client_group` = '$group[$i]' AND cb_provider  = '$prov' AND cb_transaction_id  = '$t_id'";    
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

                           </section>
                   </main>
           </body>
   </html>

                <script>

function changetable(){

var transaction_id =   $('#transaction_id').val();

$.ajax({
                    type: "POST",
                    url: "../includes/dbprocess.php", 
                    data: {
                        "show_on_approvalB1":1,
                        "transaction_idB1": transaction_id,
                        
                    },
                    success: function(result){
                        location.reload();
                }

            });

}


function verify(){

var transaction_id_approve =  $('#transaction_id').val();
var status =  "VALIDATED OR";

$.ajax({
                                            type: "POST",
                                            url: "../includes/dbprocess.php", 
                                            data: {
                                                "btn_verify_bill":1,
                                                "status_bill": status,
                                                "transaction_id_approve_bill": transaction_id_approve,
                                            },
                                            success: function(result){
                                                location.reload();
                                        }

                                    });

                                    
}


function deny(){

var transaction_id_deny =  $('#transaction_id').val();
var status =  "REUPLOAD CORRECT OR";

$.ajax({
                                            type: "POST",
                                            url: "../includes/dbprocess.php", 
                                            data: {
                                                "btn_deny_bill":1,
                                                "status_bill": status,
                                                "transaction_id_deny_bill": transaction_id_deny,
                                            },
                                            success: function(result){
                                                location.reload();
                                        }

                                    });

}









function setDataOnSelection(){
                            $('#transaction_id').val("<?php echo $_SESSION['transaction_idB1']?>");
                           
                        }
                        document.querySelectorAll('.image-viewer img').forEach(image =>{
                                image.onclick = () =>{
                                        document.querySelector('.modal-image').style.display = "block";
                                        document.querySelector('.modal-image img').src = image.getAttribute('src');
                                }
                        });
                        document.querySelector('.modal-image span').onclick = ()=>{
                                document.querySelector('.modal-image').style.display = "none";
                        }
                </script>