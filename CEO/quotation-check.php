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
                                    $query = "SELECT a.onprogressQ_transaction_id as transaction_id FROM tblqonprogress a, tblofficialreceipt b WHERE `onprogressQ_status` = 'VERIFYING OR' AND a.onprogressQ_transaction_id = b.or_transaction_id";    
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

                $t_id = $_SESSION['transaction_id1'];
                         
     
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
                                 $query = "SELECT SUM(order_product_total) as total_amount FROM `tblorder` WHERE `order_transaction_id` = '$t_id'";    
                                                $stmt = $conn->prepare($query);
                                                $stmt-> execute();
                                                $result = $stmt->get_result();  
                         ?>
                           <?php while ($row = $result->fetch_assoc()) { ?>
                                 <h1 style="text-align: center;"><strong>GRAND TOTAL: ₱ </strong><?= number_format($row['total_amount']) ?></h1>
                                 

<?php } ?>

                            <div class="data-table">
                                    
                            <?php       
                         
                         $t_id = $_SESSION['transaction_id1'];
                         
     
                         $query = "SELECT * FROM `tblorder` WHERE `order_transaction_id` = '$t_id'";    
                         $stmt = $conn->prepare($query);
                         $stmt-> execute();
                         $result = $stmt->get_result();  
                         $a=1;
                  ?>   
                        <table class="table">
                        <thead>
                                 <th hidden>ID</th>
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </thead>
                            <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>   
                                <tr>
                                    
                                    <td data-label="ID" hidden><?= $row['order_id']?></td>
                                    <td data-label="No">  <?= $a ?></td>
                                    <td data-label="Product Name"><?= $row['order_product_name']?></td>
                                    <td data-label="Price"><strong>₱ </strong><?= number_format($row['order_product_price'])?></td>
                                    <td data-label="Unit"><?= $row['order_product_unit']?></td>
                                    <td data-label="Quantity"><?= $row['order_product_quantity']?></td>
                                    <td data-label="Total"><strong>₱ </strong><?= number_format($row['order_product_total']) ?></td>
                                </tr>

                                <?php $a++; } ?>
                            </tbody>
                        </table>
                        
                    </div>
                    <?php } ?>
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
                        "show_on_approval1":1,
                        "transaction_id1": transaction_id,
                        
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
                                                "btn_verify":1,
                                                "status": status,
                                                "transaction_id_approve": transaction_id_approve,
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
                                                "btn_deny":1,
                                                "status": status,
                                                "transaction_id_deny": transaction_id_deny,
                                            },
                                            success: function(result){
                                                location.reload();
                                        }

                                    });

}









function setDataOnSelection(){
                            $('#transaction_id').val("<?php echo $_SESSION['transaction_id1']?>");
                           
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