<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>
<?php
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $bookings = $conn->prepare("SELECT * FROM bookings WHERE user_id = $id");
        $bookings->execute();
        $allBookings = $bookings->fetchAll(PDO::FETCH_OBJ);
    }
?>
<div class="container">
  <?php if(count($allBookings) > 0): ?>
   <h1> My Bookings</h1>
</div>
<table class="table mt-4">

  <thead>
    <tr>
      <th scope="col">Check in</th>
      <th scope="col">Check out</th>
      <th scope="col">Email</th>
      <th scope="col">Phone number</th>
      <th scope="col">Hotel</th>
      <th scope="col">Room</th>
      <th scope="col">Payment method</th>
      <th scope="col">Status</th>
      <th scope="col">Trx id</th>
      <th scope="col">Paid at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($allBookings as $booking): ?>
    <tr>
      <th scope="row"><?php echo $booking->check_in; ?></th>
      <td><?php echo $booking->check_out; ?></td>
      <td><?php echo $booking->email; ?></td>
      <td><?php echo $booking->phone_number; ?></td>
      <td><?php echo $booking->hotel_name; ?></td>
      <td><?php echo $booking->room_name; ?></td>
      <td><?php echo $booking->payment_method; ?></td>
      <td><?php echo $booking->status; ?></td>
      <td><?php echo $booking->trx_id; ?></td>
      <td><?php echo $booking->paid_at; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<div class="alert alert-primary" role="alert">
  <h1>No bookings found</h1>
</div>
<?php endif; ?>
</div>
<?php require "../includes/footer.php"; ?>