<?php
        
     include "includes/header.php";
     include "includes/sidebar.php";
     include "includes/footer.php";

?>
 <html>
               <body>
                    <main>
                         <section>
                         <div class="wrapper">
                                   <div class="left">
                                        <img  src="./img/profile-picture/<?= $_SESSION ['user_image']; ?>" alt="profile-picture" width="100">
                                        <h4><?=   strtoupper($_SESSION ['user_realname']); ?></h4>
                                        <p><?= strtoupper($_SESSION ['user_designation']); ?></p>
                                        <button data-modal-target="#modal" id="editData"> UPDATE PROFILE</button>
                                   </div>
                                   <div class="right">
                                        <div class="info">
                                             <h3>Information</h3>
                                             <div class="info_data">
                                                  <div class="data">
                                                       <h4>EMAIL</h4>
                                                       <p><?= $_SESSION ['user_email']; ?></p>
                                                  </div>
                                                  <div class="data">
                                                  <h4>PASSWORD</h4>
                                                       <p>***********</p>
                                                       
                                             </div>
                                             </div>
                                             <div class="info">
                                             <div class="info_data">
                                                  <div class="data">
                                                       <h4>CONTACT NUMBER</h4>
                                                       <p><?= $_SESSION ['user_contact']; ?></p>
                                                  </div>
                                                  <div class="data">
                                                  <h4>STATUS</h4>
                                                       <p>STILL ACTIVE</p>
                                                       
                                             </div>
                                             </div>
                                        </div>
                                        
                                        
                                        
                                        <div class="social_media">
                                             
                                        </div>
                                   </div>
                                   </div>


                                   <div class="modal" id="modal" style="height:580px">
                        <div class="modal-header">
                           
                          <button data-close-button class="P-close-button" style="border: none; background:none;  font-size: 1.7rem; cursor: pointer;">&times;</button>
                        </div>
                        
                        <div class="l-form">
                                <form action="../includes/dbprocess.php" class="form" Method="POST" enctype="multipart/form-data">

                        <div class="modal-logo">
                            <img src="./img/profile-picture/<?= $_SESSION ['user_image']; ?>" alt="profile-picture" id="profile-pic" width="80px" height="80px">
                            <input type="file" name="user_image" id="file">
                            <label for="file" class="uploadbtn" id="upload-button">choose profile</label>
                        </div>
                        <div class="modal-body">
                                   

                                    <div class="form__div">
                                        <input type="text" class="form__input" placeholder=" "  name="user_first" value = "<?= $_SESSION ['user_first']; ?>">
                                        <label for="" class="form__label">FIRST NAME</label>
                                    </div>
                                    <div class="form__div">
                                        <input type="text" class="form__input" placeholder=" "  name="user_last" value = "<?= $_SESSION ['user_last']; ?>">
                                        <label for="" class="form__label">LAST NAME</label>
                                    </div>

                                    <div class="form__div">
                                        <div class="control">
                                          <label for="" style="font-size:14px; color:grey;" > GENDER</label>
                                            <label class="radio">
                                              <input type="radio" name="user_sex" value="Male" id="male">
                                              MALE
                                            </label>
                                            <label class="radio">
                                              <input type="radio" name="user_sex" value="Female" id="female">
                                              FEMALE
                                            </label>
                                          </div>
                                    </div>
                              
                                    <div class="form__div">
                                        <input type="text" class="form__input" placeholder=" "  name="user_contact" value = "<?= $_SESSION ['user_contact']; ?>">
                                        <label for="" class="form__label">CONTACT NO</label>
                                    </div>
                                    <div class="form__div">
                                        <input type="email" class="form__input" placeholder=" "  name="user_email" value = "<?= $_SESSION ['user_email']; ?>">
                                        <label for="" class="form__label">EMAIL</label>
                                    </div>
                                    
                                    <div class="form__div">
                                        <input type="text" class="form__input" placeholder=" "  name="user_name" value = "<?= $_SESSION ['user_name']; ?>">
                                        <label for="" class="form__label">USERNAME</label>
                                    </div>
                                   
                                    <div class="form__div">
                                        <input type="password" class="form__input"  name="user_password" placeholder=" " >
                                        <label for="" class="form__label">PASSWORD</label>
                                    </div>
                    
                                  

                                   

                    
                                    <input type="submit" class="form__button" name="update_admin_profile" value="SAVE">
                                </form>
                            </div>
                        </div>
                      </div>
                      <div id="overlay"></div>
                     
                         </section>
                    </main>
               </body>

               <script>
                    
                    $(document).ready(function () {

                        $('#editData').on('click', function(e){

                               
                                  var sex = "<?= $_SESSION ['user_sex']; ?>";
                                  

                                    if(sex == "Male"){
                                        $('#male').prop("checked", true);
                                        $('#female').prop("checked", false);
                                    }else{
                                        $('#female').prop("checked", true);
                                        $('#male').prop("checked", false);
                                    }

                        });

                      });

                      </script>


          </html>
          <script src="JS/profile.js"></script>
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