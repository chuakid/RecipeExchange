<!doctype html>
<?php 

require_once('connstring.php'); 
	if(isset($_POST['username'])){
		$username = $_POST['username'];
		$password = md5($_POST['password']);
		$query = "SELECT * FROM user WHERE username = '$username' and password = '$password'";
		
		if($conn->query($query)->num_rows != 0){
			$row = $conn->query($query)->fetch_assoc();
			$_SESSION['user'] = $row['username'];
			$_SESSION['role'] = $row['roleId'];
			$_SESSION['displayName'] = $row['displayName'];
			$_SESSION['userid'] = $row['id'];
			header('Location: index.php');
		}
		else{
			$wrong = true;
		}
	}



?>

<html>

<head>
	<meta charset="utf-8">
	<title>Login</title>
	<link href="_css/universal.css" rel="stylesheet">
	<link href="_css/login.css" rel="stylesheet">

</head>

<body>
	<?php include_once('nav.php');
	
	?>
	<main>
		<h2>Login</h2>
		<?php if(isset($wrong)){
		echo "<h3>Wrong Username or Password</h3>";
}
	?> 
		<form id="login" action="login.php" method="post">
			<input type="text" name="username" placeholder="Username">
			<input type="password" name="password" placeholder="Password">
			<input type="submit" value="Log In">
		</form>
	</main>

	<?php include_once('footer.php'); ?>

</body>
<script src="_js/jquery-3.2.1.min.js"></script>
<script src="_js/universal.js"></script>

</html>