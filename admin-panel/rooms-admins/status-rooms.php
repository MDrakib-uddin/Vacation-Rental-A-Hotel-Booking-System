<?php require "../layout/header.php"; ?>
<?php require "../../config/config.php"; ?>
<?php
  $error = "";
  $success = "";
  $redirect = "";

  if(!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href = '".ADMINURL."/admins/login-admins.php';</script>";
 }
  if(isset($_GET['id'])) {
    $id = $_GET['id'];
    if(isset($_POST['submit'])) {
      if(empty($_POST['status'])){
        $error = "All fields are required!";
      } else {
        $status = $_POST['status'];
        $update = $conn->prepare("UPDATE rooms SET status = :status WHERE id = :id");
        $update->execute([
          "status" => $status,
          "id" => $id
        ]);
        $success = "Status updated successfully";
        $redirect = "show-rooms.php";
      }
    }
  } else {
    echo "<script>window.location.href = 'show-rooms.php';</script>";
    exit;
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
              <h5 class="card-title mb-5 d-inline" >Update Status</h5>
          <form method="POST" action="status-rooms.php?id=<?php echo isset($id) ? $id : ''; ?>" enctype="multipart/form-data">
            
                <!-- Email input -->
                <select name="status" style="margin-top: 15px;" class="form-control">
                    <option>Choose Status</option>
                    <option value="1">1</option>
                    <option value="0">0</option>
                </select>

      
                <!-- Submit button -->
                <button style="margin-top: 10px;" type="submit" name="submit" class="btn btn-primary  mb-4 text-center">update</button>

          
              </form>

            </div>
          </div>
        </div>
      </div>
<?php require "../layout/footer.php"; ?>