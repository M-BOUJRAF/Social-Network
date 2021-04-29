<?php 

require "config/connect.php";
if(isset($_SESSION['username'])){
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = $bdd -> query("SELECT * FROM users WHERE username='$userLoggedIn'");
	$user = $user_details_query -> fetch();
}else {
	header("Location: register.php");
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Feedbook</title>
	<!-- Javascript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="https://kit.fontawesome.com/6aff8824a1.js" crossorigin="anonymous"></script>
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
	<div class="top_bar">
		<div class="logo">
			<a href="index.php">Feedbook!</a>
		</div>
		<nav>
			<a href="<?php echo $userLoggedIn; ?>"><?php echo $user['first_name']; ?></a>
			<a href="index.php"><i class="fas fa-home"></i></a>
			<a href="#"><i class="far fa-envelope"></i></a>
			<a href="#"><i class="far fa-bell"></i></a>
			<a href="#"><i class="fas fa-users"></i></a>
			<a href="#"><i class="fas fa-cogs"></i></a>
			<a href="includes/handlers/logout.php"><i class="fas fa-sign-out-alt"></i></a>
		</nav>
	</div>
	<div class="wrapper">
		