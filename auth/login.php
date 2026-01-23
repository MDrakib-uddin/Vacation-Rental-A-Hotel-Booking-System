<?php require '../includes/header.php'; ?>
<?php require '../config/config.php'; ?>
<?php
   $error = "";
   $success = "";
   $redirect = "";

   if(isset($_SESSION['username'])) {
      echo "<script>window.location.href = '".APPURL."';</script>";
   }
    if (isset($_POST['submit'])) {
        if(empty($_POST['email']) || empty($_POST['password'])){
            $error = "All fields are required!";
        } else {
           $email = $_POST["email"];
           $password = $_POST["password"];
        
           $login = $conn->prepare("SELECT * FROM users WHERE email = '$email'");
           $login -> execute();
           $fetch = $login -> fetch(PDO::FETCH_ASSOC);
           if($login->rowCount() > 0){
                if(password_verify($password, $fetch['mypassword'])){
                 $_SESSION['username'] = $fetch['username'];
                 $_SESSION['id'] = $fetch['id'];
                 
                 $success = "Login Successful! Redirecting...";
                 $redirect = APPURL;
                 
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
            title: 'Login Failed',
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
            title: 'Welcome Back!',
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

    <div class="hero-wrap js-fullheight" style="background-image: url('<?php echo APPURL;?>/images/image_2.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate">
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
    	<div class="container">
	    	<div class="row justify-content-middle" style="margin-left: 397px;">
	    		<div class="col-md-6 mt-5">
						<form action="login.php" method="POST" class="appointment-form" style="margin-top: -568px;">
							<h3 class="mb-3">Login</h3>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
			    					    <input type="text" name="email" class="form-control" placeholder="Email">
			    				    </div>
								</div>
                               
                  <div class="col-md-12">
									<div class="form-group">
			    					    <input type="password" name="password" class="form-control" placeholder="Password">
			    				    </div>
								</div>
								
							
							
								<div class="col-md-12">
                    <div class="form-group">
                         <input type="submit" name= "submit" value="Login" class="btn btn-primary py-3 px-4">
                  </div>
								</div>
							</div>
	    			</form>
	    		</div>
	    	</div>
	    </div>
    </section>

<?php require '../includes/footer.php'; ?>