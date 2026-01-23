<?php require '../includes/header.php'; ?>
<?php require '../config/config.php'; ?>

<?php
if(!isset($_SESSION['registration_data']) || !isset($_SESSION['registration_otp'])) {
    echo "<script>window.location.href = '".APPURL."/auth/register.php';</script>";
    exit;
}

$emailForDisplay = $_SESSION['registration_data']['email'];

$error = "";
$success = "";
$redirect = "";

if (isset($_POST['verify'])) {
    if(empty($_POST['otp'])){
        $error = "Please enter the OTP!";
    } else {
        $entered_otp = $_POST['otp'];
        $stored_otp = $_SESSION['registration_otp'];

        if ($entered_otp == $stored_otp) {
            // Verify Success - Insert User
            $data = $_SESSION['registration_data'];
            
            try {
                $insert = $conn->prepare("INSERT INTO users (username, email, mypassword) VALUES (:username,:email, :mypassword)");
                $insert->execute([
                    "username" => $data['username'],
                    "email" => $data['email'],
                    "mypassword" => $data['mypassword']
                ]);

                // Clear Session
                unset($_SESSION['registration_data']);
                unset($_SESSION['registration_otp']);

                $success = "Registration Successful!";
                $redirect = "login.php";
            } catch (PDOException $e) {
                $error = "Database Error: " . $e->getMessage();
            }
            
        } else {
            $error = "Invalid OTP. Please try again.";
        }
    }
}
?>
<?php if($error != ""): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Verification Failed',
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
            title: 'Verified!',
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
                <form action="otp_verify.php" method="POST" class="appointment-form" style="margin-top: -568px;">
                    <h3 class="mb-3">Verify Email</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                We've sent a code to <strong><?php echo $emailForDisplay; ?></strong>. <br>

                            </div>
                            <div class="form-group">
                                <input type="text" name="otp" class="form-control" placeholder="Enter Registration Code">
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" name="verify" value="Verify" class="btn btn-primary py-3 px-4">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require '../includes/footer.php'; ?>
