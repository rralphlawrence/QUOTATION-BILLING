<?php
        
        include "includes/header.php";
        include "includes/sidebar.php";
        include "includes/footer.php";
   
   ?>
   
    <html>
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
        <body>
            <main>
                <section>
                <div class="mis-logo">
                        <img src="./img/misoutlogo.png" alt="">
                        <h3 class="mis-title">ACCOUNTS</h3>
                    </div>

                            <div class="button-div">
                                 <button class="Add-account main-btn" >ADD NEW ACCOUNT</button>
                            </div>

                            <h4 style="text-align:center; font-size:1.3rem;"> LIST OF ACCOUNTS</h4>



                            <?php       
                         $query = "SELECT * FROM `tbluseraccounts` WHERE user_role != 'Administrator' ";    
                         $stmt = $conn->prepare($query);
                         $stmt-> execute();
                         $result = $stmt->get_result();  
                         $no = 1;
                         ?>   
                            <div class="data-table">
                        <table class="table">
                            <thead>
                                <th>No</th>
                                <th>Full name</th>
                                <th>Designation</th>
                                <th>User name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </thead>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                            <tbody>

                                <tr> 
                                   <td data-label="No"><?= $no ?></td>
                                    <td data-label="Full name"><?= $row['user_firstname'] . ' ' . $row['user_lastname'] ?></td>
                                    <td data-label="Designation"><?= $row['user_designation'] ?></td>
                                    <td data-label="User name"><?= $row['user_name'] ?></td>
                                    <td data-label="Role"><?= $row['user_role'] ?></td>
                                    <td data-label="Status"><?= $row['user_status'] ?></td>
                                    <td data-label="Actions"> <button class="btn-edit" ><a data-modal-target="#modal" href="javascripit:void(0)" class="button editData" >EDIT</a></button>
                                    <button class="btn-delete"><a href="javascripit:void(0)" class="button deleteData">DELETE</a></button> 
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

                                var username = data[3];
                                var status = data[5];
                                var role = data[4];
                                var designation = data[2];

                                    $('#designation').val(designation);
                                    $('#username').val(username);
                                    $('#status').val(status);
                                    $('#role').val(role);
                                    $('#no').prop("checked", true);
                                    $('#yes').prop("checked", false);
                                   

                        });


                        $('.deleteData').on('click', function(e){

                                    e.preventDefault();

                                    $tr = $(this).closest('tr');
                                    var data = $tr.children("td").map(function(){
                                    return $(this).text();
                                    }).get();

                                    var uname = data[3];
                                    


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
                                                    "delete_user":1,
                                                    "delete_username": uname,
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
                    <div class="modal" id="modal" style="HEIGHT:600PX;">
                        <div class="modal-header">
                           
                          <button data-close-button class="close-button">&times;</button>
                        </div>
                        <div class="modal-logo">
                            <img src="img/misoutlogo.png" alt="misout logo" width="60px" height="60px">
                        </div>
                        <div class="modal-body">
                            <div class="l-form">
                                <form action="../includes/dbprocess.php" method="post" class="form">
                                   
                    <h4 style="color:grey;">Profile Info</h4>
                                    <div class="form__div">
                                        <input type="text" name="username" id="username"  class="form__input" placeholder="" hidden required>
                                        <input type="text" name="designation" id ="designation" class="form__input" placeholder="" required>
                                        <label for="" class="form__label">Designation</label>
                                    </div>
                    
                        <h4 style="color:grey;">Account Info</h4>   
                                   
                    
                                    <div class="form__div">
                                        <div class="select">
                                        <select name="role"  id="role" required>  
                                              <option value="" disabled selected>ROLE </option>
                                              <option value="Billing">Quotation/Billing</option>
                                              <option value="Bookkeeping">Bookkeeping</option>
                                            </select>
                                            <select name="status" id="status" required>  
                                              <option value="" disabled selected>STATUS</option>
                                              <option value="Active">Active</option>
                                              <option value="Inactive">Inactive</option>
                                            </select>
                                          </div>
                                    </div>

                                    <div class="form__div">
                                    <div class="control">
                                          <label for="" style="font-size:14px; color:grey;">YOU WANT TO RESET PASSWORD ?</label> <br>
                                            <label class="radio">
                                              <input type="radio" name="reset" id="yes" value="Yes">
                                              Yes
                                            </label>
                                            <label class="radio">
                                              <input type="radio" name="reset" id="no" value="No">
                                              No
                                            </label>
                                          </div>
                                    </div>

                    
                                    <input type="submit" name= "edit_user" class="form__button" value="SAVE">
                                </form>
                            </div>
                        </div>
                      </div>
                      <div id="overlay"></div>


                      <div class="modal-ac" id="modal-bag" style="height:620px;">
        <div class="modal-content">
            <div class="imgcontainer">
                <span class="close-btn">&times;</span>
                <br>
                <img src="img/misoutlogo.png" alt="" class="avatar" >
              </div>
           
              <div class="l-form">
                                <form action="../includes/dbprocess.php" method="POST" class="form">
                                   
 
                                    <div class="form__div">
                                        <input type="text" name="firstname1" class="form__input" placeholder=" " required>
                                        <label for="" class="form__label">FIRST NAME</label>
                                    </div>
                                    <div class="form__div">
                                        <input type="text" name="lastname1" class="form__input" placeholder=" " required>
                                        <label for="" class="form__label">LAST  NAME</label>
                                    </div>

                                    <div class="form__div">
                                    <div class="control">
                                          <label for="" style="font-size:14px; color:grey;">SEX</label> <br>
                                            <label class="radio">
                                              <input type="radio" name="sex1" value="Male" required>
                                              MALE
                                            </label>
                                            <label class="radio">
                                              <input type="radio" name="sex1" value="Female" required>
                                              FEMALE
                                            </label>
                                          </div>
                                    </div>
                                    <div class="form__div">
                                        <input type="text" name="designation1" class="form__input" placeholder=" " required>
                                        <label for="" class="form__label">DESIGNATION</label>
                                    </div>
                    
                        <h4 style="color:grey;">Account Info</h4>   
                        <div class="form__div">
                                        <input type="text" name="username1" class="form__input" placeholder=" " required>
                                        <label for="" class="form__label">USERNAME</label>
                                    </div>
                                    <div class="form__div">
                                        <input type="password" name="password1" class="form__input" placeholder=" " required>
                                        <label for="" class="form__label">PASSWORD</label>
                                    </div>
                    
                                    <div class="form__div">
                                        <div class="select">
                                        <select name="role1"  id="role" required>  
                                              <option value="" disabled selected>ROLE </option>
                                              <option value="Billing">Quotation/Billing</option>
                                              <option value="Bookkeeping">Bookkeeping</option>
                                            </select>
                                          </div>
                                    </div>

                                   
                    
                                    <input type="submit"  name = "btn_add_user" class="form__button" value="SAVE">
                                </form>
                            </div>


                                  </div>
                                      </div>
                                  </section>
                              </main>
                          </body>
                      </html>
          <script>
            const modal = document.getElementById('modal-bag');
            const openBtn = document.querySelector('.main-btn');
            const closeBtn = document.querySelector('.close-btn');
            // click events
            openBtn.addEventListener('click', () => {
                modal.style.display = 'block';
            });

            closeBtn.addEventListener('click', () =>{
                modal.style.display = 'none';
            });

            window.addEventListener('click', (e) =>{
                if(e.target === modal){
                    modal.style.display = 'none';
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
      </script>
      