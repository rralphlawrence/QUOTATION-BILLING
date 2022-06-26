<?php
        
     include "includes/header.php";
     include "includes/sidebar.php";
     include "includes/footer.php";

?>


  <html>
    <body>
       
        
        <!--========== CONTENTS ==========-->
        <main>
            <section>

                    <div class="mis-logo">
                        <img src="./img/misoutlogo.png" alt="">
                        <h3 class="mis-title"> ON PROGRESS</h3>
                    </div>
                    <div class="data-table">
                    <?php
                         $query = "SELECT * FROM `tblqonprogress` WHERE `onprogressQ_status` != 'DONE'";    
                         $stmt = $conn->prepare($query);
                         $stmt->execute();
                         $result = $stmt->get_result();
            ?>                     
                            <table class="table">
                            <thead>
                                <th>TRANSACTION ID</th> 
                                <th>CLIENT NAME</th> 
                                <th>DATE CREATED</th>
                                <th>SUMMARY</th>
                                <th>STATUS</th>
                                <th>ACTIONS</th>
                            </thead>
                            <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td data-label="TRANSACTION ID"><?= $row['onprogressQ_transaction_id'] ?></td>
                                    <td data-label="CLIENT NAME"><?= $row['onprogressQ_client_name'] ?></td>
                                    <td data-label="DATE CREATED"><?= $row['onprogressQ_date_created'] ?></td>
                                    <td data-label="SUMMARY" style="white-space:pre-wrap; word-wrap:break-word"><?= $row['onprogressQ_order_summary'] ?></td>
                                    <td data-label="STATUS"><?= $row['onprogressQ_status'] ?></td>
                                    <td data-label="Actions">
                                    <?php
                                        if($row['onprogressQ_status'] == 'READY TO PRINT QF'){?>
                                            <button class="btn-edit"><a  style="text-decoration:none; color:#ffff;" href="../includes/dbprocess.php?print_quotation=<?= $row['onprogressQ_transaction_id'];?>" >DOWNLOAD</i></a></button>
                                            <button class="btn-delete"><a  style="text-decoration:none; color:#ffff;" href="javascripit:void(0)" class="delete-item-confirm">REMOVE</a></button>
                                    <?php    }elseif($row['onprogressQ_status'] == 'VALIDATED OR'){?>
                                            <button class="btn-edit"><a  style="text-decoration:none; color:#ffff;" href="../includes/dbprocess.php?done_quotation=<?= $row['onprogressQ_transaction_id'];?>" class="done-item-confirm">DONE</a></button>
                                            <button class="btn-delete"><a  style="text-decoration:none; color:#ffff;" href="javascripit:void(0)" class="delete-item-confirm">REMOVE</a></button>
                                    <?php    }elseif($row['onprogressQ_status'] == 'UPLOAD OR'){?>

                                            <form action="../includes/dbprocess.php" method="POST" enctype="multipart/form-data">
                                            <button type="submit" name="upload_or" id="custom-button">UPLOAD</button>
                                            <input type="text" hidden name = "transactionID" value="<?= $row['onprogressQ_transaction_id'] ?>">
                                            <input type="file" name="fileupload_or" class="upload-bx" required> 
                                            </form> 
                                            <button class="btn-delete"><a  style="text-decoration:none; color:#ffff;" href="javascripit:void(0)" class="delete-item-confirm">REMOVE</a></button>
                                    <?php    }elseif($row['onprogressQ_status'] == 'REUPLOAD CORRECT OR'){?>

                                            <form action="../includes/dbprocess.php" method="POST" enctype="multipart/form-data">
                                            <button type="submit" name="upload_or" id="custom-button">UPLOAD</button>   
                                            <input type="text" hidden name = "transactionID" value="<?= $row['onprogressQ_transaction_id'] ?>">
                                            <input type="file" name="fileupload_or" class="upload-bx" required> 
                                            </form> 
                                            <button class="btn-delete"><a href="javascripit:void(0)"  style="text-decoration:none; color:#ffff;" class="delete-item-confirm">REMOVE</a></button>
                                    
                                    <?php   }else{ ?> 
                                        <button class="btn-delete"><a href="javascripit:void(0)"  style="text-decoration:none; color:#ffff;" class="delete-item-confirm">REMOVE</a></button>
                                        <?php }?>
                                </td>
                                </tr>
                              <?php } ?>    
                            </tbody>
                        </table>

                        
                    </div>

                    </section>
                    </main>
</body>

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
            

</html>

<script>

$(document).ready(function () {
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
        "deletefiles_btn_confirm":1,
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