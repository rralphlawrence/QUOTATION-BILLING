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
                                    $query = "SELECT DISTINCT `supplier_name` FROM `tblsupplier` WHERE `supplier_type` = 'Quotation'";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                ?>   
                                <select id="supplier" name="supplier" onchange="changetable()">    
                                <option value="wala" disabled selected>SUPPLIER</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                     <option value="<?= $row['supplier_name']; ?>"><?= strtoupper($row['supplier_name']); ?></option>
                                <?php } ?>  
                                </select>
                                          </div>
                        <div class="hero-button">
                          <a href="add2cart.php"><button class="add-supplier main-btn" > <i class='bx bx-cart'> <span></span></i>YOUR CART</button></a>
                        </div>

                   </div>



              <?php 
              
              if($_SESSION['selected_supplier'] == "wala"){

              }else{
              
              ?>

                    <div class="card-grid">
    
                    <div class="grid">
                               <?php
                                    $supplier = $_SESSION['selected_supplier'];
                                    $query = "SELECT * FROM `tblproduct` WHERE `product_supplier` = '$supplier'";    
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    $a= 1;
                                ?>   
                <!--- DITO UNG START NG WHILE LOOP--->

                <?php while ($row = $result->fetch_assoc()) { ?>      
                    <div class="grid-item">
                        <div class="card">
                        <img class="card-img" src="./img/products/<?= $row['product_image']; ?>" alt="" />
                        <div class="card-content">
                            <h1 class="card-header"><?= $row['product_name']; ?></h1>
                            <p class="card-text">
                             <strong>₱ <?= number_format($row['product_price']) ?></strong>
                            </p>

                                          
                <table>
                <thead>
                <tr>
                <th hidden>ID</th>
                <th hidden>Name</th>
                <th hidden>Description</th>
                <th hidden>Price</th>
                <th hidden>Image</th>
                <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td data-label="ID" hidden><?= $row['product_id'] ?></td>
                <td data-label="Name" hidden><?= $row['product_name'] ?></td>
                <td data-label="Description" hidden><?= $row['product_details'] ?></td>
                <td data-label="Price" hidden><strong>₱ <?= number_format($row['product_price']) ?></strong></td>
                <td data-label="Image" hidden><?= $row['product_image'] ?></td>
                <td>
                <a href="javascripit:void(0)" class="button editData" data-modal-target="#modal"> <button class="card-btn" class="">Visit <span>&rarr;</span></button></a>
                </td>
                </tr>
            </tbody>
            </table>
                        </div>
                        
                        </div>
                        
                    </div>

                    <?php $a++; } ?>
                    <!--- DITO UNG END NG CONDITION --->
                   
                    </div>
                    
                    
                    

                
                 
                    
</div>
                    
      
    </div>

                    </div>


                    <?php } ?>
                    <div class="modal-product" id="modal">
                        <div class="modal-header">
                           
                          <button data-close-button class="close-button">&times;</button>
                        </div>
                      
                        <div class="modal-body-product">
                           <div class="product-img">
                               <img id="p_image" alt="">
                           </div>
                           <div class="product-details">
                               <div class="pname" id="pname"></div>
                               <div class="details" id="details"></div>
                           </div>
                           <div class="price">
                               <div class="h6" id ="price"></div>


                               <form action="../includes/dbprocess.php" method="POST">
                                    <input type="text" name="product_id" id="p_id" hidden>
                                    <input type="number" name="product_quantity" value="1">

                                  
                           </div>
                           <button type="submit" name="btn_add_cart">ADD TO CART</button>
                           </form>

                      </div>
                    </div>
                      <div id="overlay"></div>
        


                
                   
        </section>

 </main>

 <script>

 $('.button, .editData').on('click', function(e){

e.preventDefault();

 $tr = $(this).closest('tr');
 var data = $tr.children("td").map(function(){
    return $(this).text();
}).get();

var id = data[0];
var name = data[1];
var description = data[2];
var price = data[3];
var image = data[4];

   // $('#id').val(id);
    $('#pname').html(name);
  $('#details').html(description);
    $('#price').html(price);
    $('#p_image').attr("src", "./img/products/" + image);
    $('#p_id').val(id);

});


</script>

 <script>
                        function changetable(){

                            var supplier_selected =   $('#supplier').val();

                            $.ajax({
                                                type: "POST",
                                                url: "../includes/dbprocess.php", 
                                                data: {
                                                    "show_product":1,
                                                    "supplier_selected": supplier_selected,
                                                },
                                                success: function(result){
                                                    location.reload();
                                            }

                                        });
                           
                        }

                        function setDataOnSelection(){
                            $('#supplier').val("<?php echo $_SESSION['selected_supplier']?>");
                           
                        }

    </script>
    
 </body>
</html>


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
                  const modal = button.closest('.modal-product')
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
