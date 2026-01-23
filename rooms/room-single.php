<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>
<?php
    $singleRoom = null;
    $allUtilities = [];
    $heroImage = 'image_2.jpg';
    $roomName = 'Room Not Found';
    $error = "";
    $success = "";
    $redirect = "";

    if(isset($_GET['id'])){
    $id = $_GET['id'];
    $room = $conn->prepare("SELECT * FROM rooms WHERE status = 1 AND id = :id");
    $room->execute([':id' => $id]);
    $singleRoom = $room->fetch(PDO::FETCH_OBJ);
    if ($singleRoom) {
        $heroImage = !empty($singleRoom->image) ? $singleRoom->image : 'image_2.jpg';
        $roomName = !empty($singleRoom->name) ? $singleRoom->name : 'Room Details';
        $utilities = $conn->prepare("SELECT * FROM utilities WHERE room_id = :id");
        $utilities->execute([':id' => $id]);
        $allUtilities = $utilities->fetchAll(PDO::FETCH_OBJ) ?: [];
    }
}
     if(isset($_POST["submit"])){
    if(empty($_POST["email"]) || empty($_POST["phone_number"]) || empty($_POST["full_name"]) || empty($_POST["check_in"]) || empty($_POST["check_out"]) || empty($_POST["payment_method"])){
        $error = "All fields are required";
    }else{
      $check_in = $_POST["check_in"];
      $check_out = $_POST["check_out"];
      $email = $_POST["email"];
      $phone_number = $_POST["phone_number"];
      $full_name = $_POST["full_name"];
      $hotel_name = $singleRoom->hotel_name;
      $room_name = isset($singleRoom->name) ? $singleRoom->name : '';
      $user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
      $payment_method = strtolower(trim($_POST['payment_method']));
      $allowed_methods = ['bkash','nagad','card','cash'];
      if(!in_array($payment_method, $allowed_methods, true)){
          $error = "Invalid payment method";
      } else {

      $check_in_ts = strtotime($check_in);
      $check_out_ts = strtotime($check_out);
      $today_ts = strtotime(date('Y-m-d'));

       if(!$check_in_ts || !$check_out_ts){
           $error = "Invalid date format";
    }else{
        if($check_out_ts <= $check_in_ts){
            $error = "Check-Out date must be after Check-In date";
          }else{
          if(!$user_id){
              $error = "Please login to book a room.";
          } else {
          $booking = $conn->prepare("INSERT INTO bookings (check_in, check_out,email, phone_number, full_name, hotel_name, room_name,user_id) VALUES (:check_in, :check_out,:email, :phone_number,:full_name,:hotel_name, :room_name,:user_id )");
          $success_insert = $booking->execute([
            ':check_in' => date('Y-m-d', $check_in_ts),
            ':check_out' => date('Y-m-d', $check_out_ts),
            ':email' => $email,
            ':phone_number' => $phone_number,
            ':full_name' => $full_name,
            ':hotel_name' => $hotel_name,
            ':room_name' => $room_name,
            ':user_id' => $user_id,
          ]);
          if($success_insert){
              $booking_id = $conn->lastInsertId();
              $success = "Room booked successfully. Proceed to payment.";
              $redirect = APPURL."/payment.php?booking_id=".$booking_id."&method=".$payment_method;
          }else{
              $error = "Booking failed. Please try again.";
          }
          }
        }
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
     <div class="hero-wrap js-fullheight" 
     style="background-image: url('<?php echo APPURL;?>/images/<?php echo $heroImage; ?>');" 
     data-stellar-background-ratio="0.5">

      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
          <div class="col-md-7 ftco-animate">
          	<h2 class="subheading">Welcome to Vacation Rental</h2>
          	<h1 class="mb-4"><?php echo $roomName; ?></h1>
            <!-- <p><a href="#" class="btn btn-primary">Learn more</a> <a href="#" class="btn btn-white">Contact us</a></p> -->
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
    	<div class="container">
    		<div class="row justify-content-end">
    			<div class="col-lg-4">
					<form action="room-single.php?id=<?php echo $id; ?>" method="POST" class="appointment-form" style="margin-top: -568px;">
						<h3 class="mb-3">Book this room</h3>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input type="text" name="email" class="form-control" placeholder="Email">
			
								</div>
							</div>
						   
							<div class="col-md-12">
								<div class="form-group">
									<input type="text" name="full_name" class="form-control" placeholder="Full Name">
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<input type="text" name="phone_number" class="form-control" placeholder="Phone Number">
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
								<div class="input-wrap">
									<div class="icon"><span class="ion-md-calendar"></span></div>
										<input type="text" name="check_in" class="form-control appointment_date-check-in" placeholder="Check-In">
									</div>
								</div>
							</div>
						
							<div class="col-md-6">
									<div class="form-group">
										<div class="icon"><span class="ion-md-calendar"></span></div>
										<input type="text" name="check_out" class="form-control appointment_date-check-out" placeholder="Check-Out">
									</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<select name="payment_method" class="form-control">
										<option value="bkash">bKash</option>
										<option value="nagad">Nagad</option>
										<option value="card">Credit/Debit Card</option>
										<option value="cash">Cash on Arrival</option>
									</select>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<input type="submit" name="submit" value="Book and Pay Now" class="btn btn-primary py-3 px-4">
								</div>
							</div>
						</div>
				</form>
    			</div>
    		</div>
    	</div>
    </section>
   


  


    <section class="ftco-section bg-light">
			<div class="container">
				<div class="row no-gutters">
					<div class="col-md-6 wrap-about">
						<div class="img img-2 mb-4" style="background-image: url(<?php echo APPURL;?>/images/image_2.jpg);">
						</div>
						<h2>The most recommended vacation rental</h2>
						<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
					</div>
					<div class="col-md-6 wrap-about ftco-animate">
	        	  <div class="heading-section">
	        	  	<div class="pl-md-5">
			            <h2 class="mb-2">What we offer</h2>
	            	</div>
	          	</div>
	          	<div class="pl-md-5">
							<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
				<div class="row">
				<?php if (!empty($allUtilities)): ?>
		            <?php foreach($allUtilities as $utility): ?>
		            <div class="services-2 col-lg-6 d-flex w-100">
		              <div class="icon d-flex justify-content-center align-items-center">
		            		<span class="<?php echo $utility->icon;?>"></span>
		              </div>
		              <div class="media-body pl-3">
		                <h3 class="heading"><?php echo $utility->name;?></h3>
		                <p><?php echo $utility->description;?></p>
		              </div>
		               </div> 
		            <?php endforeach; ?>
		            <?php else: ?>
		            <div class="col-12"><p>No utilities available for this room.</p></div>
		            <?php endif; ?>
		            </div>
	          	</div>  
					</div>
				</div>
			</div>
		</section>
		
		<section class="ftco-intro" style="background-image: url(<?php echo APPURL;?>/images/image_2.jpg);" data-stellar-background-ratio="0.5">
			<div class="overlay"></div>
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-9 text-center">
						<h2>Ready to get started</h2>
						<p class="mb-4">It’s safe to book online with us! Get your dream stay in clicks or drop us a line with your questions.</p>
						<p class="mb-0"><a href="#" class="btn btn-primary px-4 py-3">Learn More</a> <a href="#" class="btn btn-white px-4 py-3">Contact us</a></p>
					</div>
				</div>
			</div>
		</section>


<?php require "../includes/footer.php"; ?>