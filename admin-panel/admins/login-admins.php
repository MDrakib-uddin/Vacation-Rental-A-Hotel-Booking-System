<?php require "../layout/header.php"; ?>
<?php require "../../config/config.php"; ?>
<?php
  $error = "";
  $success = "";
  $redirect = "";

  if(isset($_SESSION['adminname'])) {
    echo "<script>window.location.href = '".ADMINURL."';</script>";
 }
  if (isset($_POST['submit'])) {
      if(empty($_POST['email']) || empty($_POST['password'])){
          $error = "All fields are required!";
      } else {
         $email = $_POST["email"];
         $password = $_POST["password"];
      
         $login = $conn->prepare("SELECT * FROM admins WHERE email = '$email'");
         $login -> execute();
         $fetch = $login -> fetch(PDO::FETCH_ASSOC);
         if($login->rowCount() > 0){
              if(password_verify($password, $fetch['mypassword'])){
               $_SESSION['adminname'] = $fetch['adminname'];
               $_SESSION['id'] = $fetch['id'];

               $success = "Login Successful!";
               $redirect = ADMINURL;
              } else {
                  $error = "Incorrect Password!";
              }
          } else {
              $error = "Email not found!"; 
        }
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
              <h5 class="card-title mt-5">Login</h5>
              <form method="POST" class="p-auto" action="login-admins.php">
                  <!-- Email input -->
                  <div class="form-outline mb-4">
                    <input type="email" name="email" id="form2Example1" class="form-control" placeholder="Email" />
                   
                  </div>

                  
                  <!-- Password input -->
                  <div class="form-outline mb-4">
                    <input type="password" name="password" id="form2Example2" placeholder="Password" class="form-control" />
                    
                  </div>



                  <!-- Submit button -->
                  <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">Login</button>

                 
                </form>

            </div>
       </div>
<?php require "../layout/footer.php"; ?>