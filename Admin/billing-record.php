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
                                <input type="text" class="searchbar__input" name="searchRecordB_ID" placeholder="Search records">
                                <button type="submit" name="searchRecordB" class="searchbar__button">
                                <i class='bx bx-search-alt-2' ></i>
                                </button>
                            </div>
                        </form>

                        </div>  
                        <h3 style="text-align:right;"><a href="../includes/dbprocess.php?download_quotationOR=<?php echo $_SESSION['transactionBID']; ?>" style="color:#919094;">Download OR <i class='bx bxs-receipt'></i></a> </h3>
                        <div class="data-table">
                        <h3>Transaction ID:   </h3> <span><p> <?php echo $_SESSION['transactionBID']; ?></p>
                        </div>
                        <?php




$prov = $_SESSION['clientNameB'];
$id = $_SESSION['transactionBID'];


    
$sqlforNoAccount = "SELECT DISTINCT cb_client_group FROM tblcreatedbill , tblbonprogress  WHERE (cb_provider = '$prov' AND cb_transaction_id = '$id') AND (onprogressB_status = 'DONE' AND cb_transaction_id = onprogressB_transaction_id)";
$sqlrun = mysqli_query($conn, $sqlforNoAccount);

if(mysqli_num_rows($sqlrun)>0){

 $sqlforNoAccount = "SELECT DISTINCT cb_client_group FROM tblcreatedbill , tblbonprogress  WHERE (cb_provider = '$prov' AND cb_transaction_id = '$id') AND (onprogressB_status = 'DONE' AND cb_transaction_id = onprogressB_transaction_id)";
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
?>
     <div class="data-table">
     <div class="monthly-label">
                <h3><?php echo $_SESSION['clientNameB']?> MONTHLY BILLING </h3>
        </div>
<?php
for ($i=0; $i < $total_no; $i++) { 


?>

        <div class="monthly-label">
                
        <h4><?= $group[$i]?></h4>
        </div>


   

        <?php
$query = "SELECT * FROM tblcreatedbill , tblbonprogress  WHERE (cb_client_group = '$group[$i]' AND cb_provider = '$prov' AND cb_transaction_id = '$id') AND (onprogressB_status = 'DONE' AND cb_transaction_id = onprogressB_transaction_id)";    
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
                                    <td data-label="ID" hidden><?=$row['cb_id']?></td>
                                    <td data-label="No"><?=$no ?></td>
                                    <td data-label="Account No"><?=$row['cb_account_number']?></td>
                                    <td data-label="Client Name"><?=$row['cb_client_name']?></td>
                                    <td data-label="Service Promo"><?=$row['cb_service_promo']?></td>
                                    <td data-label="Billing Period"><?=$row['cb_billing_period']?></td>
                                    <td data-label="Billing Price"><strong>₱ <?= number_format($row['cb_billing_price']) ?></strong></td>
                                    <td data-label="Client Payment"><strong>₱ <?= number_format($row['cb_client_payment']) ?></strong></td>
<td data-label="OR">
<a href="../includes/dbprocess.php?download_quotationIndivdualOR=<?php echo $row['cb_receipt']; ?>" style="color:yellow;" href=""><i class='bx bxs-receipt'></i></a> 
</td>

</tr>
<?php $no++; } ?>
</tbody>
</table>
<div class="Total-amount">
<table>
<?php
$query = "SELECT SUM(cb_client_payment) as total_payment, SUM(cb_billing_price) as total_price FROM `tblcreatedbill` , tblbonprogress  WHERE (cb_client_group = '$group[$i]' AND cb_provider = '$prov' AND cb_transaction_id = '$id') AND (onprogressB_status = 'DONE' AND cb_transaction_id = onprogressB_transaction_id)";    
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
<?php } ?>
                        </table>

                     
                    </div>

                    
                    </section>
                
                </main>
            </body>
        </html>