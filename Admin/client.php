 <?php
        
     include "includes/header.php";
     include "includes/sidebar.php";
     include "includes/footer.php";

?>
        <!--========== CONTENTS ==========-->
<html>
    <body onload="setSelected()">
        <main>
            <section>
                <div class="hero-section">
                    <div class="logo-section">
                        <img src="img/misoutlogo.png" alt="" width="80" height="80px">
                        <h4>ALWAYS THE FASTEST</h4>
                    </div>

                    <div class="hero-button">
                        <button class="add-supplier"  data-modal-target="#modal">ADD CLIENT</button>
                    </div>

                </div>

                <!--=============================================================================================================================-->

                <div class="sorter">
                    <div class="select">
                        <select id="client-type" onchange="showData()">  
                            <option value="Billing" >BILLING</option>
                            <option value="Quotation">QUOTATION</option>
                        </select>
                    </div>
                </div>

                <div class="data-table">

                    <?php
                        $selectedItem = $_SESSION['client-selectedItem'];      
                        $query = "SELECT `client_id`, `client_name`, `client_address`, `client_contact_number`, `client_contact_email`, `client_group` FROM `tblclient` WHERE `client_type` = '$selectedItem'";    
                        $stmt = $conn->prepare($query);
                        $stmt-> execute();
                        $result = $stmt->get_result();      
                    ?>

                    <table class="table">
                        <thead>
                            
                                <th hidden>ID</th>
                                 <th>No</th>
                                <th>Client Name</th>
                            <th>Address</th>
                            <th>Contact No.</th>
                            <th>E-mail</th>
                            <?php 
                                if ($selectedItem == "Billing") {
                                    echo "<th>Group</th>";
                                }
                            ?>
                            <th>Actions</th>
                        </thead>
                            
                        <tbody>
                            <?php $no=1; while ($row = $result->fetch_assoc()) { ?>
                                <tr>  <td data-label="ID" hidden><?= $row['client_id']?></td>
                                        <td data-label="No"><?= $no ?></td>
                                    <td data-label="Name of supplier"><?= $row['client_name']?></td>
                                    <td data-label="Address"><?= $row['client_address']?></td>
                                    <td data-label="Contact no."><?= $row['client_contact_number']?></td>
                                    <td data-label="E-mail"><?= $row['client_contact_email']?></td>
                                    <?php if ($selectedItem == "Billing") { ?>
                                        <td data-label="Group"><?= $row['client_group']?></td>
                                    <?php } ?>
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

                <!--=============================================================================================================================-->

                <div class="modal" id="modal" style="height:530px";>
                    <div class="modal-header">   
                        <button data-close-button class="close-button">&times;</button>
                    </div>

                    <div class="modal-logo">
                        <img src="img/misoutlogo.png" alt="misout logo" width="60px" height="60px">
                    </div>

                    <div class="select-btn">
                        <button onclick="show1()"> BILLING</button>
                        <button onclick="show2()">QUOTATION</button>
                    </div>

                    <div class="modal-body">
                        <div class="l-form">
                            
                                <div class="pag-walapa" id="walapa">
                                    <h5>PLEASE SELECT YOUR DESIRED FORM</h5>
                                </div>

                                <form action="../includes/dbprocess.php" class="form" Method="POST">
                                <div class="billing" id="billing">
                                    <div class="form__div">
                                        <input type="text" class="form__input" placeholder=" " id="billclientname" name="client-billing-supplierName" required>
                                        <label for="" class="form__label">NAME OF CLIENT</label>
                                    </div>
                    
                                    <div class="form__div">
                                        <input type="text" class="form__input" placeholder=" " id="billclientadd" name="client-billing-address" required>
                                        <label for="" class="form__label">ADDRESS</label>
                                    </div>

                                    <div class="form__div">
                                        <input type="text" class="form__input" placeholder=" " id="billclientcontact" name="client-billing-contactNumber" required>
                                        <label for="" class="form__label">CONTACT NO</label>
                                    </div>
                    
                                    <div class="form__div">
                                        <input type="email" class="form__input" placeholder=" " id="billclientemail" name="client-billing-email" required>
                                        <label for="" class="form__label">EMAIL</label>
                                    </div>

                                    <div class="form__div">
                                        <div class="select"> 
                                            <select name="client-billing-group" id="billclientgroup" required>
                                                <option value="Internal">INTERNAL</option>
                                                <option value="External">EXTERNAL</option>
                                                <option value="Client">CLIENT</option>
                                                <option value="CHO">CHO</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <input type="hidden" class="form__input" placeholder=" " id="billclientid" name="client-billing-id">
                                    <input type="submit" class="form__button" value="SAVE" name="client-billing-btnSaveclient">

                                    

                                </div>
                                </form>

                                

                                <!--=============================================================================================================================-->
                              
                                <form action="../includes/dbprocess.php" class="form" Method="POST">
                                <div class="quotation" id="quotation">
                                    <div class="form__div">
                                        <input type="text" class="form__input" placeholder=" " id="quotclientname" name="client-quotation-clientName" required>
                                        <label for="" class="form__label">CLIENT NAME</label>
                                    </div>
                        
                                    <div class="form__div">
                                        <input type="text" class="form__input" placeholder=" " id="quotclientadd" name="client-quotation-address" required>
                                        <label for="" class="form__label">ADDRESS</label>
                                    </div>

                                    <div class="form__div">
                                        <input type="text" class="form__input" placeholder=" " id="quotclientcontact"name="client-quotation-contactNumber" required>
                                        <label for="" class="form__label">CONTACT NO</label>
                                    </div>
                        
                                    <div class="form__div">
                                        <input type="email" class="form__input" placeholder=" "  id="quotclientemail" name="client-quotation-email" required>
                                        <label for="" class="form__label">EMAIL</label>
                                    </div>
                                    <input type="hidden" class="form__input" placeholder=" " id="quotclientid"name="client-quotation-id">
                                    <input type="submit" class="form__button" value="SAVE" name="client-quotation-btnSaveclient">
                                    
                                </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                    <div id="overlay"></div> 
                </section>
            </main>
        </body>
</html>

<script>
                    
                    $(document).ready(function () {

                        $('.button, .editData').on('click', function(e){

                                e.preventDefault();

                                 $tr = $(this).closest('tr');
                                 var data = $tr.children("td").map(function(){
                                    return $(this).text();
                                }).get();


                                var contacttype =  $('#client-type').val();
                                
                                var contactid = data[0];
                                var contactname = data[2];
                                var contactaddress = data[3];
                                var contactnumber = data[4];
                                var contactemail = data[5];
                                var contactgroup = data[6];
                                
                                if(contacttype == "Billing"){
                                    document.getElementById('billing').style.display ='block';
                                    document.getElementById('walapa').style.display ='none';
                                    document.getElementById('quotation').style.display ='none';

                                    $('#billclientid').val(contactid);
                                    $('#billclientname').val(contactname);
                                    $('#billclientemail').val(contactemail);
                                    $('#billclientcontact').val(contactnumber);
                                    $('#billclientgroup').val(contactgroup);
                                    $('#billclientadd').val(contactaddress);

                                }else{
                                    document.getElementById('billing').style.display ='none';
                                    document.getElementById('walapa').style.display ='none';
                                    document.getElementById('quotation').style.display ='block';


                                    $('#quotclientid').val(contactid);
                                    $('#quotclientname').val(contactname);
                                    $('#quotclientemail').val(contactemail);
                                    $('#quotclientcontact').val(contactnumber);
                                    $('#quotclientadd').val(contactaddress);

                                    

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
                                                    "delete_btn_client":1,
                                                    "delete_id_client": id,
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
 
            function show1(){
          document.getElementById('billing').style.display ='block';
          document.getElementById('walapa').style.display ='none';
          document.getElementById('quotation').style.display ='none';
        }
        function show2(){
          document.getElementById('quotation').style.display = 'block';
          document.getElementById('walapa').style.display ='none';
          document.getElementById('billing').style.display ='none';
        }
      
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

function setSelected(){
    $('#client-type').val("<?php echo $_SESSION['client-selectedItem'] ?>");
}

function showData(){
    var selectedtype = $('#client-type').val();

    $.ajax({
        type: "POST",
        url: "../includes/dbprocess.php",
        data: {
            "showdata-clients": 1,
            "selectedtype": selectedtype,
        },
        success: function(result){
            location.reload();
        }
    });
}
      </script>
        <script src="js/responsive.js"></script>
        <script src="js/chart.js"></script>
       
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