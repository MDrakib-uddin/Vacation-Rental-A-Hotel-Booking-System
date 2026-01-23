<?php
   session_start();
   define("APPURL","http://localhost/hotel-booking");
   define("HOTELSIMAGES","http://localhost/hotel-booking/admin-panel/hotels-admins/hotel_images");
   define("ROOMIMAGES","http://localhost/hotel-booking/admin-panel/rooms-admins/room_images");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Vacation Rental Hotel Booking System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,500,500i,600,600i,700,700i&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
 
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/animate.css">
    
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/magnific-popup.css">

    <link rel="stylesheet" href="<?php echo APPURL;?>/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/jquery.timepicker.css">

    <link rel="stylesheet" href="<?php echo APPURL;?>/css/flaticon.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
<div class="wrap bg-dark text-white py-2 top-header-wrap">
			<div class="container">
				<div class="row justify-content-between align-items-center">
						<div class="col-md-8 col-12 d-flex align-items-center justify-content-center justify-content-md-start mb-2 mb-md-0">
							<p class="mb-0 phone d-flex align-items-center mr-4">
                                <span class="fa fa-phone mr-2 text-primary"></span> 
                                <a href="tel://01609980287" class="text-white small">01609980287</a>
                            </p>
                            <p class="mb-0 email d-flex align-items-center">
                                <span class="fa fa-envelope mr-2 text-primary"></span> 
                                <a href="mailto:rakibuddinraki1014@gmail.com" class="text-white small">rakibuddinraki1014@gmail.com</a>
                            </p>
						</div>
						<div class="col-md-4 col-12 d-flex justify-content-center justify-content-md-end">
							<div class="social-media">
				    		<p class="mb-0 d-flex">
				    			<a href="#" class="d-flex align-items-center justify-content-center rounded-circle mr-2" style="width: 30px; height: 30px; background: rgba(255,255,255,0.1);"><span class="fa fa-facebook"><i class="sr-only">Facebook</i></span></a>
				    			<a href="#" class="d-flex align-items-center justify-content-center rounded-circle mr-2" style="width: 30px; height: 30px; background: rgba(255,255,255,0.1);"><span class="fa fa-twitter"><i class="sr-only">Twitter</i></span></a>
				    			<a href="#" class="d-flex align-items-center justify-content-center rounded-circle mr-2" style="width: 30px; height: 30px; background: rgba(255,255,255,0.1);"><span class="fa fa-instagram"><i class="sr-only">Instagram</i></span></a>
				    			<a href="#" class="d-flex align-items-center justify-content-center rounded-circle" style="width: 30px; height: 30px; background: rgba(255,255,255,0.1);"><span class="fa fa-dribbble"><i class="sr-only">Dribbble</i></span></a>
				    		</p>
			        </div>
						</div>
				</div>
			</div>
		</div>
		<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	    	<a class="navbar-brand" href="<?php echo APPURL;?>">Vacation<span> Rentals</span></a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="fa fa-bars"></span> Menu
	      </button>
	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	        	<li class="nav-item active"><a href="<?php echo APPURL;?>" class="nav-link">Home</a></li>
	        	<li class="nav-item"><a href="<?php echo APPURL;?>/about.php" class="nav-link">About</a></li>
	        	<li class="nav-item"><a href="<?php echo APPURL;?>/services.php" class="nav-link">Services</a></li>
	          <li class="nav-item"><a href="<?php echo APPURL;?>/contact.php" class="nav-link">Contact</a></li>
              <?php if(!isset($_SESSION['username'])): ?>
	          <li class="nav-item"><a href="<?php echo APPURL;?>/auth/login.php" class="nav-link">Login</a></li>
	          <li class="nav-item"><a href="<?php echo APPURL;?>/auth/register.php" class="nav-link">Register</a></li>
              <?php else: ?> 
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo$_SESSION['username']; ?>
             </a>
             <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="<?php echo APPURL;?>/users/booking.php?id=<?php echo $_SESSION['id'];?>">Your Booking</a></li>
             <!-- <li><a class="dropdown-item" href="#">Another action</a></li> -->
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo APPURL;?>/auth/logout.php">Logout</a></li>
                     </ul>
                 </li>
                <?php endif; ?>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->