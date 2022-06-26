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
                <main>
                    <section>
                        <h1></h1>
                        <a href="./Quotation.php" style="text-decoration:none; color:#ffff;"><i class='bx bx-arrow-back'></i>BACK</a>
                            <div class="product-container cart-page">


                            <?php       
                         $query = "SELECT * FROM `tblcart`";    
                         $stmt = $conn->prepare($query);
                         $stmt-> execute();
                         $result = $stmt->get_result();  
                         ?>  
                                <table>
                                    <tr>
                                        <th hidden>ID</th>
                                        <th hidden>Price</th>
                                        <th hidden>Quantity</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td hidden><?= $row['cart_id']?></td>
                                        <td hidden><?= $row['cart_product_price']?></td>
                                        <td hidden><?= $row['cart_product_quantity']?></td>
                                        <td>
                                            <div class="cart-info">
                                                <img src="./img/products/<?= $row['cart_product_image']; ?>" alt="" width="90px">
                                                <div class="cart-details">
                                                    <P><?= $row['cart_product_name']?></P>
                                                    <strong>₱ </strong><?= number_format($row['cart_product_price']) ?>
                                                    <br>
                                                    <a href="javascripit:void(0)" class="deleteData" style="color:red">REMOVE</a>
                                            </div>
                                            </div>
                                        </td>
                                        <td><input type="number" value="<?= $row['cart_product_quantity']?>" class="qty"></td>
                                        <td><strong>₱ </strong><?= number_format($row['cart_product_total']) ?></td>
                                    </tr>
                                    <?php }?>

                                   
                                </table>

                                <?php       
                         $query = "SELECT SUM(cart_product_total) as total_amount FROM `tblcart`";    
                         $stmt = $conn->prepare($query);
                         $stmt-> execute();
                         $result = $stmt->get_result();  
                         ?>  
                                <div class="total-price">
                                    <table>
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td>Grand total</td>
                                            <td><strong>₱ </strong><?= number_format($row['total_amount']) ?>
                                            
                                            
                                        </td>
                                        </tr>
                                        <?php }?>

                                    </table>
                                </div>
                            </div>
                          
                            <div class="proceed">
                                <form method="POST" action="../includes/dbprocess.php">
                                <div class="select">
                                <?php
                                    $query = "SELECT DISTINCT `client_name` FROM `tblclient` WHERE `client_type` = 'Quotation'";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                <select id="client" name="client" onchange="changetable()" required>    
                                <option value="" disabled selected>SELECT CLIENT</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['client_name']; ?>"><?= strtoupper($row['client_name']); ?></option>
                                <?php } ?>  
                                </select>
                                </div>
                                <button type="submit" name="proceed_quotation">PROCEED TO QUOTATION </button>
                                </form>
                         
                                
                            </div>


                    </section>
                </main>

                <script>
   $(document).ready(function () {

$('.qty').on('change', function(e){

e.preventDefault();

 $tr = $(this).closest('tr');
 var data = $tr.children("td").map(function(){
    return $(this).text();
}).get();


var id = data[0];
var price = data[1];
var quantity = $( this ).val();



$.ajax({
                                                type: "POST",
                                                url: "../includes/dbprocess.php", 
                                                data: {
                                                    "update_cart":1,
                                                    "update_product_id": id,
                                                    "update_product_qty": quantity,
                                                    "update_product_price": price,
                                                },
                                                success: function(result){
                                                    location.reload();
                                            }

                                        });
});


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
                "delete_cart":1,
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


});


</script>

            </body>





                


        </html>