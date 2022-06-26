<?php
        
        include "includes/header.php";
        include "includes/sidebar.php";
        include "includes/footer.php";
?>
        <!--========== CONTENTS ==========-->
 <html>   
    <body onload="setDataOnSelection()">    
        <main>
            <section>

               <div class="hero-section">
                    <div class="logo-section">
                        <img src="img/misoutlogo.png" alt="" width="80" height="80px">
                        <h4>ALWAYS THE FASTEST</h4>
                    </div>

                    <div class="hero-button">
                        <button class="add-supplier"  data-modal-target="#modal" id="newData">ADD PRODUCT</button>
                    </div>
               </div>

               <!--=============================================================================================================================-->

               <div class="sorter">

                    <?php 
                        $query = "SELECT `supplier_name` FROM `tblsupplier` WHERE `supplier_type` = 'Quotation'";    
                        $stmt = $conn->prepare($query);
                        $stmt-> execute();
                        $result = $stmt->get_result();
                    ?>

                            <div class="select">
                            <?php
                                    $query = "SELECT DISTINCT `supplier_name` FROM `tblsupplier` WHERE `supplier_type` = 'Quotation'";    
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
                </div>

                <!--=============================================================================================================================-->

                <div class="data-table">

                    <?php 
                        $selectedSupplier = $_SESSION['supplierName'];
                        $query = "SELECT  `product_id`, `product_image`,`product_name`,`product_details`,`product_unit`,`product_price` FROM `tblproduct` WHERE `product_supplier` = '$selectedSupplier'";    
                        $stmt = $conn->prepare($query);
                        $stmt-> execute();
                        $result = $stmt->get_result();      
                    ?>

                    <table class="table">
                        <thead>
                            <th hidden>ID</th>
                            <th>No</th>
                            <th>Image</th>
                            <th>Name</th> 
                            <th>Details</th>
                            <th>Price</th>
                            <th>Unit</th>
                            <th hidden>Image1</th>
                            <th>Actions</th>
                        </thead>

                        <tbody>
                            <?php $no = 1 ; while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td data-label="ID"  hidden><?= $row['product_id']?></td>
                                    <td data-label="No"><?= $no ?></td>
                                    <td data-label="Image"><img src="img/products/<?= $row['product_image']?>" alt="" width="90px"></td>
                                    <td data-label="Name"><?= $row['product_name']?></td>
                                    <td data-label="Details" style="text-align: justify;"><?= $row['product_details']?></td>
                                    <td data-label="Price"><strong>₱ <?= number_format($row['product_price']) ?></strong></td>
                                    <td data-label="Unit"><?= $row['product_unit']?></td>
                                    <td data-label="filename"  hidden><?= $row['product_image']?></td>
                                    <td data-label="Actions">
                                        <button class="btn-edit"><a href="javascripit:void(0)" class="button editData" data-modal-target="#modal">EDIT</a></button> 
                                        <button class="btn-delete"><a href="javascripit:void(0)" class="delete-item-confirm">REMOVE</a></button></td>
                                </tr>
                            <?php $no++; } ?>
                        </tbody>
                    </table>
                </div>

                <!--=============================================================================================================================-->

                <div class="modal" id="modal" style="height:520px;">

                    <?php 
                        
                        $query = "SELECT `supplier_name` FROM `tblsupplier` WHERE `supplier_type` = 'Quotation'";    
                        $stmt = $conn->prepare($query);
                        $stmt-> execute();
                        $result = $stmt->get_result();  

                    ?>

                    <div class="modal-header">                     
                        <button data-close-button class="close-button">&times;</button>
                    </div>

                    <div class="modal-logo">
                        <img src="img/misoutlogo.png" alt="misout logo" width="60px" height="60px">
                    </div>

                    <div class="modal-body">
                        <div class="l-form">
                            <form action="../includes/dbprocess.php" class="form" Method="POST" enctype="multipart/form-data">

                                <div class="form__div">
                                    <input type="text" class="form__input" placeholder=" " name="productName" id="productName" required>
                                    <label for="" class="form__label">PRODUCT NAME</label>
                                </div>
                
                                <div class="form__div">
                                    <input type="file" id="file" name="file" hidden="hidden"/>
                                    <button type="button" id="custom-button" name="btnChoosefile">CHOOSE A FILE</button>
                                    <span id="custom-text" name="productImage">No file chosen, yet.</span>
                                </div>

                                <div class="form__div">
                                    <input type="text" class="form__input" placeholder=" " name="productDetails" id="productDetails" required>
                                    <label for="" class="form__label">PRODUCT DETAILS</label>
                                </div>
                
                                <div class="form__div">
                                      <div class="select">
                            <?php
                                    $query = "SELECT DISTINCT `supplier_name` FROM `tblsupplier` WHERE `supplier_type` = 'Quotation'";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                <select id="productSupplier" name="productSupplier" required>    
                                <option value="wala" disabled selected>SELECT SUPPLIER</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['supplier_name']; ?>"><?= strtoupper($row['supplier_name']); ?></option>
                                <?php } ?>  
                                </select>
                         </div>
                                </div>

                                <div class="form__div">
                                    <input type="text" class="form__input" placeholder=" " name="productPrice" id="productPrice" onkeypress="return onlyNumberKey(event)" required >
                                    <label for="" class="form__label">PRICE</label>
                                </div>

                                <div class="form__div">
                                    <input type="text" class="form__input" placeholder=" " name="productUnit" id="productUnit" required>
                                    <label for="" class="form__label">UNIT</label>
                                </div>
                                <input type="hidden" class="form__input" placeholder=" " name="productImage" id="productImage">
                                <input type="hidden" class="form__input" placeholder=" " name="productID" id="productID">
                                <input type="submit" class="form__button" value="SAVE" name="btnSaveproduct">

                            </form>
                        </div>
                    </div>
                </div>
                      
                <div id="overlay"></div>
                     
            </section>
        </main>
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
                    
                    $(document).ready(function () {

                        $('.button, .editData').on('click', function(e){

                                e.preventDefault();

                                 $tr = $(this).closest('tr');
                                 var data = $tr.children("td").map(function(){
                                    return $(this).text();
                                }).get();

                                var productID = data[0];
                                var productImage = data[7];
                                var productName = data[3];
                                var productDetails = data[4];
                                var productPrice = data[5].substring(2).replace(/,/g, "");;
                                var productUnit = data[6];
                                var productSupplier = $('#supplier').val();

                                    $('#productID').val(productID);
                                    $('#productUnit').val(productUnit);
                                    $('#productPrice').val(productPrice);
                                    $('#productSupplier').val(productSupplier);
                                    $('#productDetails').val(productDetails);
                                    $('#productName').val(productName);
                                    $('#productImage').val(productImage);

                        });



                        $('#newData').on('click', function(e){

                                e.preventDefault();

                                var productID = '';
                                var productImage = '';
                                var productName = '';
                                var productDetails = '';
                                var productPrice = '';
                                var productUnit = '';
                                var productFile = '';
                                var productSupplier = $('#supplier').val();

                                    $('#productID').val(productID);
                                    $('#productUnit').val(productUnit);
                                    $('#productPrice').val(productPrice);
                                    $('#productSupplier').val(productSupplier);
                                    $('#productDetails').val(productDetails);
                                    $('#productName').val(productName);
                                    $('#file').val(productFile);
                                    $('#productImage').val(productImage);
                                   

                        });






                        $('.delete-item-confirm').on('click', function(e){

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
                                                    "delete_btn_product":1,
                                                    "delete_id_product": id,
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
<!--========== MAIN JS ==========-->

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

            function setDataOnSelection(){
                            $('#supplier').val("<?php echo $_SESSION['supplierName']?>");
                           
                        }

            function changetable(){
                var selectedSupplier = $('#supplier').val();

                $.ajax({
                    type: "POST",
                    url: "../includes/dbprocess.php",
                    data: {
                        "showdataProducts":1,
                        "selectedsupplier":selectedSupplier,
                    },
                    success: function(result){
                        location.reload();
                    }
                });
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