<?php
	require('loader.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Class 1</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/main.css">
</head>
<body>
	<div id="overlay">
		<div class="container-fluid options">
			<div class="col-md-4">
				<div id="check-in-out" class="option" onclick="check_in_out_show()">
					<p>Check in/out</p>
				</div>
			</div>
			<div class="col-md-4">
				<p id="name">Bobby Name</p>
				<div id="view-day-report" class="option">		
					<p>View report</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="exit"><span onclick="overlay_hide()">&#x2573</span></div>
				<div id="change-pin" class="option">
					<p>Change pin</p>
				</div>
			</div>
		</div>
	</div>

	<div id="overlay-check-in-out">
		<div class="container-fluid options">
			<div class="col-md-4">
				<div class="back"><span onclick="check_in_out_hide()">&#x261A</span></div>
				<div id="check-in-out" class="option" onclick="check_in_out_show()">
					<p>Check in/out</p>
				</div>
			</div>
			<div class="col-md-4">
				<div id="check-in" class="option">		
					<p>Check in</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="exit"><span onclick="overlay_hide()">&#x2573</span></div>
				<div id="check-out" class="option">
					<p>Check out</p>
				</div>
			</div>
		</div>
	</div>

	<div id="content">
		<div id="top" class="container-fluid">
			<div id="test" class="col-md-4">
				<img id="logo" src="img/logo.png" alt="logo" height="50%" width="50%">
			</div>
			<div class="col-md-4">
				<h1 id="room-name">Room 1</h1>
			</div>
			<div class="col-md-4"></div>
		</div>
		<div id="main" class="container-fluid">
			<?php echo makeRowSet($children) ?>
		</div>
	</div>
	<script src="js/main.js"></script>
</body>
</html>