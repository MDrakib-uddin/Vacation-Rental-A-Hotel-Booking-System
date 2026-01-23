<?php require "includes/header.php"; ?>
<?php require "config/config.php"; ?>
<?php
$bookingId = isset($_GET['booking_id']) ? (int) $_GET['booking_id'] : 0;
$method = isset($_GET['method']) ? strtolower(trim($_GET['method'])) : '';
$allowed = ['bkash','nagad','card','cash'];
if($bookingId <= 0 || !in_array($method, $allowed, true)){
	echo '<div class="container py-5"><div class="alert alert-danger">Invalid payment request.</div></div>';
	require "includes/footer.php";
	exit;
}
$booking = null;
try{
	$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = :id");
	$stmt->execute([':id' => $bookingId]);
	$booking = $stmt->fetch(PDO::FETCH_OBJ);
}catch(Exception $e){
	// ignore, show generic page
}

$roomPrice = null; // per-night price
$totalAmount = null; // total for stay
$currency = 'BDT';
if($booking){
	// compute nights
	$nights = 1;
	try{
		$ci = new DateTime($booking->check_in ?? '');
		$co = new DateTime($booking->check_out ?? '');
		$diff = $ci->diff($co);
		$nights = max(1, (int)$diff->days);
	}catch(Exception $e){ $nights = 1; }
	// try to fetch price from rooms table by room_name
	try{
		$pstmt = $conn->prepare("SELECT price FROM rooms WHERE name = :name LIMIT 1");
		$pstmt->execute([':name' => ($booking->room_name ?? '')]);
		$row = $pstmt->fetch(PDO::FETCH_ASSOC);
		if($row && isset($row['price'])){
			$roomPrice = (float)$row['price'];
			$totalAmount = $roomPrice * $nights;
		}
	}catch(Exception $e){ /* ignore */ }
}
?>
<div class="container py-5">
	<h2 class="mb-4">Complete your payment</h2>
	<?php if($booking): ?>
	<p class="mb-2"><strong>Booking #:</strong> <?php echo htmlspecialchars($bookingId, ENT_QUOTES); ?></p>
	<p class="mb-2"><strong>Room:</strong> <?php echo htmlspecialchars($booking->room_name ?? '', ENT_QUOTES); ?></p>
	<p class="mb-2"><strong>Stay:</strong> <?php echo htmlspecialchars(($booking->check_in ?? '').' → '.($booking->check_out ?? ''), ENT_QUOTES); ?></p>
	<?php if($roomPrice !== null): ?>
	<p class="mb-2"><strong>Price per night:</strong> <?php echo number_format($roomPrice, 2); ?> <?php echo $currency; ?></p>
	<p class="mb-4"><strong>Total (<?php echo (int)($nights ?? 1); ?> night<?php echo ((int)($nights ?? 1))>1?'s':''; ?>):</strong> <?php echo number_format($totalAmount, 2); ?> <?php echo $currency; ?></p>
	<?php else: ?>
	<p class="mb-4"><em>Price information not available.</em></p>
	<?php endif; ?>
	<?php endif; ?>

	<?php if($method === 'cash'): ?>
		<div class="alert alert-info">You selected Cash on Arrival. Your booking is pending. Please pay at the hotel<?php echo $totalAmount!==null?(' (Amount: '.number_format($totalAmount,2).' '.$currency.')'):''; ?>.</div>
	<?php elseif($method === 'bkash'): ?>
		<div class="card mb-3"><div class="card-body">
			<h5 class="card-title">bKash Payment</h5>
			<p>Send <?php echo $totalAmount!==null?('<strong>'.number_format($totalAmount,2).' '.$currency.'</strong>'):('the total amount'); ?> to our bKash Merchant Number: <strong>01XXXXXXXXX</strong> and enter the Transaction ID below.</p>
			<form method="post" action="payment_success.php">
				<input type="hidden" name="booking_id" value="<?php echo (int)$bookingId; ?>" />
				<input type="hidden" name="method" value="bkash" />
				<?php if($totalAmount!==null): ?><input type="hidden" name="amount" value="<?php echo htmlspecialchars($totalAmount, ENT_QUOTES); ?>" /><?php endif; ?>
				<div class="form-group mb-2"><input name="trx_id" class="form-control" placeholder="bKash Transaction ID" required></div>
				<button class="btn btn-primary">Confirm Payment</button>
			</form>
		</div></div>
	<?php elseif($method === 'nagad'): ?>
		<div class="card mb-3"><div class="card-body">
			<h5 class="card-title">Nagad Payment</h5>
			<p>Send <?php echo $totalAmount!==null?('<strong>'.number_format($totalAmount,2).' '.$currency.'</strong>'):('the total amount'); ?> to our Nagad Merchant Number: <strong>01YYYYYYYYY</strong> and enter the Transaction ID below.</p>
			<form method="post" action="payment_success.php">
				<input type="hidden" name="booking_id" value="<?php echo (int)$bookingId; ?>" />
				<input type="hidden" name="method" value="nagad" />
				<?php if($totalAmount!==null): ?><input type="hidden" name="amount" value="<?php echo htmlspecialchars($totalAmount, ENT_QUOTES); ?>" /><?php endif; ?>
				<div class="form-group mb-2"><input name="trx_id" class="form-control" placeholder="Nagad Transaction ID" required></div>
				<button class="btn btn-primary">Confirm Payment</button>
			</form>
		</div></div>
	<?php elseif($method === 'card'): ?>
		<div class="card mb-3"><div class="card-body">
			<h5 class="card-title">Card Payment</h5>
			<p>Card gateway integration placeholder. Implement Stripe/SSLCommerz as needed<?php echo $totalAmount!==null?(' (Amount: '.number_format($totalAmount,2).' '.$currency.')'):''; ?>.</p>
			<a class="btn btn-primary" href="#">Pay with Card</a>
		</div></div>
	<?php endif; ?>
</div>
<?php require "includes/footer.php"; ?>
