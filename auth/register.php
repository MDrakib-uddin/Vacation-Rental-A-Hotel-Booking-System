<?php require '../includes/header.php'; ?>
<?php require '../config/config.php'; ?>

<?php
if(isset($_SESSION['username'])) {
     echo "<script>window.location.href = '".APPURL."';</script>";
 }
    
    $error = "";
    $success = "";
    $redirect = "";

    if (isset($_POST['submit'])) {
        if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])){
            $error = "All fields are required!";
        } else {
           $username = $_POST["username"];
           $email = $_POST["email"];
           $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
           
           $check = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
           $check->execute([
               "email" => $email,
               "username" => $username
           ]);

           if($check->rowCount() > 0) {
               $error = "Email or Username already registered!";
           } else {
               $otp = rand(100000, 999999);
               $_SESSION['registration_data'] = [
                   "username" => $username,
                   "email" => $email,
                   "mypassword" => $password
               ];
               $_SESSION['registration_otp'] = $otp;

               require_once '../includes/Mailer.php';
               $mailer = new Mailer();
               
               $sendResult = $mailer->sendOTP($email, $otp);
               
               if($sendResult === true) {
                   $success = "OTP Sent Successfully! Please check your email.";
                   $redirect = "otp_verify.php";
               } else {
                   $error = "Failed to send OTP: " . $sendResult;
               }
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
            timer: 2000,
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
						<form action="register.php"  method = "POST" class="appointment-form" style="margin-top: -568px;">
							<h3 class="mb-3">Register</h3>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
			    					    <input type="text" name="username" class="form-control" placeholder="Username">
			    				    </div>
								</div>
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
                      <input type="submit" name="submit" value="Register" class="btn btn-primary py-3 px-4">
                 </div>
								</div>
							</div>
	    			</form>
	    		</div>
	    	</div>
	    </div>
    </section>

<?php require '../includes/footer.php'; ?>