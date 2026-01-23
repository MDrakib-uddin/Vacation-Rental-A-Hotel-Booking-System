<?php require "../layout/header.php"; ?>
<?php require "../../config/config.php"; ?>
<?php
  if(!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href = '".ADMINURL."/admins/login-admins.php';</script>";
  }
  $bookings = $conn->prepare("SELECT * FROM bookings");
  $bookings->execute();
  $allBookings = $bookings->fetchAll(PDO::FETCH_OBJ);
?>
          <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-4 d-inline">Bookings</h5>
            
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Serial</th>
                    <th scope="col">check in</th>
                    <th scope="col">check out</th>
                    <th scope="col">email</th>
                    <th scope="col">phone number</th>
                    <th scope="col">full name</th>
                    <th scope="col">hotel name</th>
                    <th scope="col">room name</th>
                    <th scope="col">status</th>
                    <th scope="col">payment</th>
                    <th scope="col">created at</th>
                    <th scope="col">trx id</th>
                    <th scope="col">paid at</th>
                    <th scope="col">delete</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($allBookings as $booking): ?>
                  <tr>
                    <th scope="row"><?php echo $booking->id; ?></th>
                    <td><?php echo $booking->check_in; ?></td>
                    <td><?php echo $booking->check_out; ?></td>
                    <td><?php echo $booking->email; ?></td>
                    <td><?php echo $booking->phone_number; ?></td>
                    <td><?php echo $booking->full_name; ?></td>
                    <td><?php echo $booking->hotel_name; ?></td>
                    <td><?php echo $booking->room_name; ?></td>
                    <td><?php echo $booking->status; ?></td>
                    <td><?php echo $booking->payment_method; ?></td>
                    <td><?php echo $booking->created_at; ?></td>
                    <td><?php echo $booking->trx_id; ?></td>
                    <td><?php echo $booking->paid_at; ?></td>
                     <td><a href="status-bookings.php?id=<?php echo $booking->id; ?>" class="btn btn-warning  text-center ">status</a></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table> 
            </div>
          </div>
        </div>
      </div>


<?php require "../layout/footer.php"; ?>