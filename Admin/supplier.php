<?php    
    include "includes/header.php";
    include "includes/sidebar.php";
    include "includes/footer.php";

    
?>

<?php if (isset($_SESSION['response']) && $_SESSION['response'] !='') { ?>
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

<!--========== CONTENTS ==========-->
<body onload="setSelected()">
<main>
    <section>

        <!--=============================================================================================================================-->

        <div class="hero-section">
            <div class="logo-section">
                <img src="img/misoutlogo.png" alt="" width="80" height="80px">
                <h4>ALWAYS THE FASTEST</h4>
            </div>
            <div class="hero-button">
                <button class="add-supplier" id= "newData" data-modal-target="#modal">ADD SUPPLIER</button>
            </div>
        </div>
        
        <div class="sorter">
            <div class="select">
                <select id="suppliertype" name="selected-item" onchange="showData()">  
                    <option value="Quotation">QUOTATION</option>
                    <option value="Billing">BILLING</option>
                </select>
            </div>   
        </div>

        <!--=============================================================================================================================-->

        <div class="data-table">

            <?php
                $selectedItem = $_SESSION['supplier-selectedItem'];
                         
                $query = "SELECT * FROM `tblsupplier` WHERE `supplier_type` = '$selectedItem'";    
                $stmt = $conn->prepare($query);
                $stmt-> execute();
                $result = $stmt->get_result();  
                         
            ?>

            <table class="table">

                <thead>
                    <th hidden>ID</th>
                    <th>No</th>
                    <th>Supplier Name</th>
                    <th>Address</th>
                    <th>Contact person</th>
                    <th>Contact no.</th>
                    <th>E-mail</th>
                    <th>Actions</th>
                </thead>

                <tbody>
                    <?php $no=1; while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td data-label="ID" hidden><?= $row['supplier_id']?></td>
                            <td data-label="No"><?= $no ?></td>
                            <td data-label="Supplier Name"><?= $row['supplier_name']?></td>
                            <td data-label="Address"><?= $row['supplier_address']?></td>
                            <td data-label="Contact person"><?= $row['supplier_contact_person']?></td>
                            <td data-label="Contact no."><?= $row['supplier_contact_number']?></td>
                            <td data-label="E-mail"><?= $row['supplier_contact_email']?></td>
                            <td data-label="Actions"> 

                            <button class="btn-edit">
                                <a href="javascripit:void(0)" class="button editData" data-modal-target="#modal">
                                    EDIT
                                </a>
                            </button> 

                                <button class="btn-delete">
                                <a href="javascripit:void(0)" class="button deleteData">DELETE </a>
                                </button>                              

                            </td>
                        </tr>
                    <?php $no++; } ?>
                </tbody>
            </table>
        </div>

        <script>
                    
                    $(document).ready(function () {

                        $('.button, .editData').on('click', function(e){

                                e.preventDefault();

                                 $tr = $(this).closest('tr');
                                 var data = $tr.children("td").map(function(){
                                    return $(this).text();
                                }).get();

                                var contactid = data[0];
                                var contactperson = data[4];
                                var contactname = data[2];
                                var contactemail = data[6];
                                var contactnumber = data[5];
                                var contactaddress = data[3];
                                var contacttype =  $('#suppliertype').val();

                                    $('#contactid').val(contactid);
                                    $('#contactname').val(contactname);
                                    $('#contactemail').val(contactemail);
                                    $('#contactnumber').val(contactnumber);
                                    $('#contactperson').val(contactperson);
                                    $('#contactaddress').val(contactaddress);

                                    if(contacttype == "Quotation"){
                                       
                                            $('#Q').prop("checked", true);
                                            $('#B').prop("checked", false);
                                    }else{
                                            $('#Q').prop("checked", false);
                                            $('#B').prop("checked", true);
                                    }

                        });



                        $('#newData').on('click', function(e){

                                e.preventDefault();

                                var contactid = "";
                                var contactperson = "";
                                var contactname = "";
                                var contactemail = "";
                                var contactnumber = "";
                                var contacttype = "";
                                var contactaddress = "";

                                    $('#contactid').val(contactid);
                                    $('#contactname').val(contactname);
                                    $('#contactemail').val(contactemail);
                                    $('#contactnumber').val(contactnumber);
                                    $('#contactperson').val(contactperson);
                                    $('#contactaddress').val(contactaddress);
                                    $('#Q').prop("checked", false);
                                    $('#B').prop("checked", false);
                                   

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
                                                    "delete_btn_supplier":1,
                                                    "delete_id_supplier": id,
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

                        


                        
    
function showData(){
    var selectedtype = $('#suppliertype').val();

    $.ajax({
        type: "POST",
        url: "../includes/dbprocess.php",
        data: {
            "supplier-showData":1,
            "selectedType":selectedtype,
        },
        success: function(result){
            location.reload();
        }
    });
}

function setSelected(){
    $('#suppliertype').val("<?php echo $_SESSION['supplier-selectedItem'] ?>");
}



</script>


        <!--=============================================================================================================================-->

        <div class="modal" id="modal" style="height:520px">
            <div class="modal-header">
                <button data-close-button class="close-button">&times;</button>
            </div>

            <div class="modal-logo">
                <img src="img/misoutlogo.png" alt="misout logo" width="60px" height="60px">
            </div>

            <div class="modal-body">
                <div class="l-form">
                    <form action="../includes/dbprocess.php" class="form" Method="POST">
                        <div class="form__div">
                            <input id="contactname" type="text" class="form__input" placeholder=" " name="supplier-name" required>
                            <label for="" class="form__label">NAME OF SUPPLIER</label>
                        </div>

                        <div class="form__div">
                            <input id="contactaddress" type="text" class="form__input" placeholder=" " name="supplier-address" required>
                            <label for="" class="form__label">ADDRESS</label>
                        </div>

                        <div class="form__div">
                            <input id="contactperson" type="text" class="form__input" placeholder=" " name="supplier-contact-person" required>
                            <label for="" class="form__label">CONTACT PERSON</label>
                        </div>

                        <div class="form__div">
                            <input id="contactnumber" type="text" class="form__input" placeholder=" " name="supplier-contact-number" required>
                            <label for="" class="form__label">CONTACT NO.</label>
                        </div>

                        <div class="form__div">
                            <input id="contactemail" type="email" class="form__input" placeholder=" " name="supplier-email" required>
                            <label for="" class="form__label">EMAIL</label>
                        </div>

                        <div class="form__div">
                            <div class="control">
                                <label for="" style="font-size:14px; color:grey;"> FOR QUOTATION</label> <br>

                                <label class="radio">
                                    <input type="radio" name="supplier-type"  id="Q" value="Quotation">Yes
                                </label>

                                <label class="radio">
                                    <input type="radio" name="supplier-type" id="B" value="Billing">No
                                </label>
                            </div>
                        </div>
                        <input type="hidden" class="form__button" id="contactid" name="id_sup">
                        <input type="submit" class="form__button" value="SAVE" name="btnSave">
                    </form>
                </div>
            </div>
        </div>


        

<div id="overlay"></div>

</section>
</main>

<!--========== MAIN JS ==========-->


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

</script>

</body>
</html>