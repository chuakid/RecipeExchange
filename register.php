<!doctype html>
<?php 
	require_once('connstring.php'); 
if(isset($_SESSION['role'])){
	header('Location: index.php');
}
	if(isset($_POST['username'])){
		$username = $_POST['username'];
		$password = md5($_POST['password']);
		$displayname= $_POST['displayname'];
		$query = "SELECT username FROM user WHERE username = '$username'";
		
		if($conn->query($query)->num_rows == 0){
			$query = "INSERT INTO user(username,password,roleId,displayName) VALUES('$username','$password','1','$displayname')";
			$conn->query($query);
		}
		else{
			$taken = true;
		}
	}
		 ?>
<html>

<head>
	<meta charset="utf-8">
	<title>Register</title>
	<link href="_css/universal.css" rel="stylesheet">
	<link href="_css/register.css" rel="stylesheet">

</head>

<body>
	<?php include_once('nav.php');
	
	?>
	<main>
		<h2>Registration</h2>
		<?php
		if ( isset( $taken ) ) {
			echo '<h3>Username taken</h3>';
		}
		?>
		<form id="register" action="register.php" method="post">
			<input type="text" name="displayname" placeholder="Display Name(can be changed later)">
			<input type="text" name="username" placeholder="Username">
			<input type="password" name="password" placeholder="Password">
			<input type="submit" value="Register">
		</form>
	</main>
	<?php include_once('footer.php'); ?>
</body>
<script src="_js/jquery-3.2.1.min.js"></script>
<script src="_js/universal.js"></script>

</html>