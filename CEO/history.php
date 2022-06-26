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
                <div class="mis-logo">
                        <img src="./img/misoutlogo.png" alt="">
                        <h3 class="mis-title"> HISTORY LOGS</h3>
                    </div>




                            <?php       
                         $query = "SELECT * FROM `tblhistory` ORDER BY `tblhistory`.`history_timestamp` DESC LIMIT 125";    
                         $stmt = $conn->prepare($query);
                         $stmt-> execute();
                         $result = $stmt->get_result();  
                         $no = 1;
                         ?>   
                            <div class="data-table">
                        <table class="table">
                            <thead>
                              
                                <th>No</th>
                                <th>Timestamp</th>
                                <th>Action</th>
                                <th>User In-Charge</th>
                                <th>Role</th>
                            </thead>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                            <tbody>

                                <tr> 
                                   <td data-label="No"><?= $no ?></td>
                                    <td data-label="Timestamp"><?= $row['history_timestamp']?></td>
                                    <td data-label="Action"><?= $row['history_action'] ?></td>
                                    <td data-label="User"><?= $row['history_user'] ?></td>
                                    <td data-label="Role"><?= $row['history_app_type'] ?></td>
                                  </td>
                                </tr>
                                <?php $no++; } ?>
                                
                               
                            </tbody>
                        </table>

                        
                    </div>
                   
                                  </section>
                              </main>
                          </body>
                      </html>
         