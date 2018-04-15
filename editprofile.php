<!doctype html>
<?php 
require_once('connstring.php'); 
if(!isset($_SESSION['role'])){
	header('Location: login.php');
}
$id = $_SESSION['userid'];
$query = "SELECT * FROM USER WHERE id = '$id'";
$row = $conn->query($query)->fetch_assoc();

$displayName = $row['displayName'];

?>

<html>

<head>
	<meta charset="utf-8">
	<title>Profile</title>
	<link href="_css/universal.css" rel="stylesheet">
	<link href="_css/editprofile.css" rel="stylesheet">

</head>

<body>
	<?php include_once('nav.php');
	
	?>
	<main>
		<h2>Edit Profile</h2>
		<?php if(isset($wrong)){
		echo "<h3>Wrong Username or Password</h3>";
}
	?>
		<form id="edit" action="editprofile.php" method="post">
			<label>Display Name:</label>
			<input type="text" name="displayName" placeholder="<?php echo $displayName ?>">
			<input type="submit" value="Edit">
		</form>
	</main>

	<?php include_once('footer.php'); ?>

</body>
<script src="_js/jquery-3.2.1.min.js"></script>
<script src="_js/universal.js"></script>

</html>