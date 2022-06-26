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
                   <div class="search__container">
                   <form action="../includes/dbprocess.php" method="post">
                            <div class="searchbar">
                                <input type="text" class="searchbar__input" name="searchRecordQ_ID" placeholder="Search records">
                                <button type="submit" name="searchRecordQ" class="searchbar__button">
                                <i class='bx bx-search-alt-2' ></i>
                                </button>
                            </div>
                        </form>

                        </div>
                        
                        <h3 style="text-align:right;"><a href="../includes/dbprocess.php?download_quotationOR=<?php echo $_SESSION['transactionQID']; ?>" style="color:#919094;">Download OR <i class='bx bxs-receipt'></i></a> </h3>
                        <div class="data-table">
                        <h3> Transaction ID: </h3> <span><p><?php echo $_SESSION['transactionQID']; ?></p></span>
                        <h3> Client Name: </h3> <span><p><?php echo $_SESSION['clientNameQ']; ?></p></span>
                        </div>
                        <?php       
                         
                            $clientName = $_SESSION['clientNameQ'];
                            $transactionID = $_SESSION['transactionQID'];
                         
     
                         $query = "SELECT  * FROM `tblorder`, tblqonprogress WHERE `order_transaction_id` = '$transactionID' AND (onprogressQ_status = 'DONE' AND `order_transaction_id` = onprogressQ_transaction_id)";    
                         $stmt = $conn->prepare($query);
                         $stmt-> execute();
                         $result = $stmt->get_result();  
                         $no = 1;
                         ?>   
                      
                     
                        <div class="data-table">
                        <table class="table">
                            <thead>
                                <th>No</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Price</th>
                                <th>Total</th>
                            </thead>
                            <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>

                                
                                <tr>
                                    <td data-label="No"><?= $no ?></td>
                                    <td data-label="Product"><?= $row['order_product_name'] ?></td>
                                    <td data-label="Quantity"><?= $row['order_product_quantity'] ?></td>
                                    <td data-label="Unit"><?= $row['order_product_unit'] ?></td>
                                    <td data-label="Price"><strong>₱ </strong><?= number_format($row['order_product_price']) ?></td>
                                    <td data-label="Total"><strong>₱ </strong><?= number_format($row['order_product_total']) ?></td>
                                </tr>
                              
                            <?php $no++;  $noData = "meron";} ?>
                               
                            </tbody>
                        </table>

                        <div class="Total-amount">
                                <?php
                                
                         $query = "SELECT SUM(order_product_total) as TOtal_Amount FROM `tblorder`, tblqonprogress WHERE `order_transaction_id` = '$transactionID' AND (onprogressQ_status = 'DONE' AND `order_transaction_id` = onprogressQ_transaction_id)";    
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
            </body>
        </html>