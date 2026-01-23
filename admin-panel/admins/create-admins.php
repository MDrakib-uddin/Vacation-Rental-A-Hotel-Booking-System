<?php require "../layout/header.php"; ?>
<?php require "../../config/config.php"; ?>
<?php
  $error = "";
  $success = "";
  $redirect = "";

  if(!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href = '".ADMINURL."/admins/login-admins.php';</script>";
 }
 if (isset($_POST['submit'])) {
  if(empty($_POST['adminname']) || empty($_POST['email']) || empty($_POST['password'])){
      $error = "All fields are required!";
  } else {
     $adminname = $_POST["adminname"];
     $email = $_POST["email"];
     $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
     
     $insert = $conn->prepare("INSERT INTO admins (adminname, email, mypassword) VALUES (:adminname,:email, :mypassword)");
     $insert -> execute([
         "adminname" => $adminname,
         "email" => $email,
         "mypassword"=> $password
      ]);
      $success = "Admin created successfully";
      $redirect = "admins.php";
  }
}
?>
<?php if($error != ""): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?php echo addslashes($error); ?>',
        });
    });
</script>
<?php endif; ?>
<?php if($success != ""): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo addslashes($success); ?>',
            timer: 1500,
            showConfirmButton: false
        }).then(function() {
            <?php if($redirect != ""): ?>
            window.location.href = '<?php echo $redirect; ?>';
            <?php endif; ?>
        });
    });
</script>
<?php endif; ?>
       <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-5 d-inline">Create Admins</h5>
          <form method="POST" action="create-admins.php" enctype="multipart/form-data">
                <!-- Email input -->
                <div class="form-outline mb-4 mt-4">
                  <input type="email" name="email" id="form2Example1" class="form-control" placeholder="email" />
                 
                </div>

                <div class="form-outline mb-4">
                  <input type="text" name="adminname" id="form2Example1" class="form-control" placeholder="username" />
                </div>
                <div class="form-outline mb-4">
                  <input type="password" name="password" id="form2Example1" class="form-control" placeholder="password" />
                </div>

               
            
                
              


                <!-- Submit button -->
                <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">create</button>

          
              </form>

            </div>
          </div>
        </div>
      </div>
  </div>
<script type="text/javascript">

</script>
</body>
</html>