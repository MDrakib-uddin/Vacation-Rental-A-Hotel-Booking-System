<?php require "includes/header.php"; ?>
<?php require "config/config.php"; ?>
<?php
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
	echo '<div class="container py-5"><div class="alert alert-danger">Invalid request.</div></div>';
	require "includes/footer.php"; exit;
}

$bookingId = isset($_POST['booking_id']) ? (int) $_POST['booking_id'] : 0;
$method = isset($_POST['method']) ? strtolower(trim($_POST['method'])) : '';
$trxId = isset($_POST['trx_id']) ? trim($_POST['trx_id']) : '';
$allowed = ['bkash','nagad','card','cash'];

if($bookingId <= 0 || !in_array($method, $allowed, true)){
	echo '<div class="container py-5"><div class="alert alert-danger">Invalid payment payload.</div></div>';
	require "includes/footer.php"; exit;
}

if(in_array($method, ['bkash','nagad'], true) && $trxId === ''){
	echo '<div class="container py-5"><div class="alert alert-danger">Transaction ID is required for mobile payments.</div></div>';
	require "includes/footer.php"; exit;
}

// Detect available columns in bookings
$columns = [];
try{
	$colStmt = $conn->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'bookings'");
	$colStmt->execute();
	$columns = array_map(function($r){ return $r['COLUMN_NAME']; }, $colStmt->fetchAll(PDO::FETCH_ASSOC));
}catch(Exception $e){ /* ignore */ }

$fields = [];
$params = [':id' => $bookingId];

if(in_array('payment_method', $columns, true)){
	$fields[] = 'payment_method = :payment_method';
	$params[':payment_method'] = $method;
}

$statusValue = ($method === 'cash') ? 'pending' : 'paid';
if(in_array('status', $columns, true)){
	$fields[] = 'status = :status';
	$params[':status'] = $statusValue;
}

if($trxId !== '' && in_array('trx_id', $columns, true)){
	$fields[] = 'trx_id = :trx_id';
	$params[':trx_id'] = $trxId;
}

if(in_array('paid_at', $columns, true) && $statusValue === 'paid'){
	$fields[] = 'paid_at = :paid_at';
	$params[':paid_at'] = date('Y-m-d H:i:s');
}

$updated = false;
if(!empty($fields)){
	$sql = 'UPDATE bookings SET '.implode(', ', $fields).' WHERE id = :id';
	try{
		$u = $conn->prepare($sql);
		$updated = $u->execute($params);
	}catch(Exception $e){
		$updated = false;
	}
}
?>
<div class="container py-5">
	<h2 class="mb-4">Payment <?php echo ($statusValue === 'paid') ? 'Successful' : 'Recorded'; ?></h2>
	<p class="mb-3">Booking #<?php echo htmlspecialchars($bookingId, ENT_QUOTES); ?> has been updated.</p>
	<ul>
		<li>Method: <?php echo htmlspecialchars($method, ENT_QUOTES); ?></li>
		<?php if($trxId !== ''): ?><li>Transaction ID: <?php echo htmlspecialchars($trxId, ENT_QUOTES); ?></li><?php endif; ?>
		<li>Status: <?php echo htmlspecialchars($statusValue, ENT_QUOTES); ?></li>
	</ul>
	<?php if(!$updated): ?>
		<div class="alert alert-warning mt-3">Note: Database columns for payment details may be missing. You can add them to persist payment info.</div>
	<?php endif; ?>
	<a class="btn btn-primary mt-3" href="<?php echo APPURL; ?>/index.php">Back to Home</a>
</div>
<?php require "includes/footer.php"; ?>
