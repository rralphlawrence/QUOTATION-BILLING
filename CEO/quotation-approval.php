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
                        <h3 class="mis-title">FOR APPROVAL</h3>
                    </div>

                            <div class="button-div">
                            <div class="sorter">
                  
                                <div class="select">
                                <?php
                                    $query = "SELECT `onprogressQ_transaction_id` FROM `tblqonprogress` WHERE `onprogressQ_status` = 'ON APPROVAL'";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                <select id="transaction_id" name="transaction_id" onchange="changetable()" required>    
                                <option value="wala" disabled selected>TRANSACTION ID</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['onprogressQ_transaction_id']; ?>"><?= $row['onprogressQ_transaction_id'] ?></option>
                                <?php } ?>  
                                </select>
                                </div>

                                <button class="Add-account main-btn" onclick="approve()" >APPROVED</button>
                            </div>
                                 
                                 
                            </div>


                            <?php 

                $t_id = $_SESSION['transaction_id'];
                         
     
        if($t_id == 'wala'){

        }else{


            
            $query = "SELECT SUM(order_product_total) as total_amount FROM `tblorder` WHERE `order_transaction_id` = '$t_id'";    
                           $stmt = $conn->prepare($query);
                           $stmt-> execute();
                           $result = $stmt->get_result();  


?>
                                
                           <?php while ($row = $result->fetch_assoc()) { ?>
                        <h1 style="text-align: center;"><strong>GRAND TOTAL: ₱ </strong><?= number_format($row['total_amount']) ?></h1>
                                                

<?php }?>


                            <div class="data-table">

                            <?php       
                         
                         $t_id = $_SESSION['transaction_id'];
                         
     
                         $query = "SELECT * FROM `tblorder` WHERE `order_transaction_id` = '$t_id'";    
                         $stmt = $conn->prepare($query);
                         $stmt-> execute();
                         $result = $stmt->get_result();  
                         $a=1;
                  ?>   
                        <table class="table" id="data_table" >
                            <thead>
                                 <th hidden>ID</th>
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>   
                                <tr>
                                    
                                    <td data-label="ID" hidden><?= $row['order_id']?></td>
                                    <td data-label="No">  <?= $a ?></td>
                                    <td data-label="Product Name"><?= $row['order_product_name']?></td>
                                    <td data-label="Price"><input type="text" onchange="updateData()"  value="<?= $row['order_product_price']?>" class="price" style="background-color: #1B191B; color:white;"></td>
                                    <td data-label="Unit"><?= $row['order_product_unit']?></td>
                                    <td data-label="Quantity"><?= $row['order_product_quantity']?></td>
                                    <td data-label="Total"><strong>₱ </strong><?= number_format($row['order_product_total']) ?></td>
                                    <td data-label="Actions"> <button class="btn-delete"><a href="javascripit:void(0)" class="btn-delete">DELETE</a></button></td>
                                </tr>

                                <?php $a++; }?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>

                    </section>
                </main>

                <script>

function approve(){

    var transaction_id_approve =  $('#transaction_id').val();
    var status =  "READY TO PRINT QF";

    $.ajax({
                                                type: "POST",
                                                url: "../includes/dbprocess.php", 
                                                data: {
                                                    "btn_approve":1,
                                                    "status": status,
                                                    "transaction_id_approve": transaction_id_approve,
                                                },
                                                success: function(result){
                                                    location.reload();
                                            }

                                        });

}

                        function changetable(){

                            var transaction_id =   $('#transaction_id').val();

                            $.ajax({
                                                type: "POST",
                                                url: "../includes/dbprocess.php", 
                                                data: {
                                                    "show_on_approval":1,
                                                    "transaction_id": transaction_id,
                                                    
                                                },
                                                success: function(result){
                                                    location.reload();
                                            }

                                        });
                           
                        }

                        function setDataOnSelection(){
                            $('#transaction_id').val("<?php echo $_SESSION['transaction_id']?>");
                           
                        }

                        $('.price').on('change', function(e){

e.preventDefault();

 $tr = $(this).closest('tr');
 var data = $tr.children("td").map(function(){
    return $(this).text();
}).get();


var id = data[0];
var quantity = data[5];
var price = $( this ).val();
var transaction_id_approve =  $('#transaction_id').val();


$.ajax({
                                                type: "POST",
                                                url: "../includes/dbprocess.php", 
                                                data: {
                                                    "update_order_ceo":1,
                                                    "transaction_id_approve": transaction_id_approve,
                                                    "update_product_id": id,
                                                    "update_product_qty": quantity,
                                                    "update_product_price": price,
                                                },
                                                success: function(result){
                                                    location.reload();
                                            }

                                        });
});


$('.btn-delete').on('click', function(e){

e.preventDefault();

$tr = $(this).closest('tr');
var data = $tr.children("td").map(function(){
return $(this).text();
}).get();

var id = data[0];
var transaction_id_approve =  $('#transaction_id').val();


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
                "delete_order_ceo":1,
                "transaction_id_approve": transaction_id_approve,
                "delete_cart_id": id,
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