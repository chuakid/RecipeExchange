<!doctype html>
<?php 
	require_once('connstring.php'); 
if(!isset($_SESSION['role']) || !isset($_GET['id'])){
	header('Location: index.php');
}
if(isset($_POST['description'])){
	$description = $_POST['description'];
	$submitter = $_SESSION['userid'];
	$recipe = $_POST['recipe'];
	$stars = $_POST['stars'];
	
	$query = "INSERT INTO review(stars,review,submitter,recipe) VALUES('$stars','$description','$submitter','$recipe')";
	$conn->query($query);
	
	header('Location: viewrecipe.php?id=' . $recipe);
}
		 ?>
<html>

<head>
	<meta charset="utf-8">
	<title>Review</title>
	<link href="_css/universal.css" rel="stylesheet">
	<link href="_css/review.css" rel="stylesheet">

</head>

<body>
	<?php include_once('nav.php');
	
	?>
	<main>
		<h2>Write Review</h2>
		<?php
		if ( isset( $taken ) ) {
			echo '<h3>Username taken</h3>';
		}
		?>
		<form id="review" action="writeReview.php" method="post">
			<div>
				<button><img src="Assets/Icons_OrangeStar.png"></button>
				<button><img src="Assets/Icons_GrayStar.png"></button>
				<button><img src="Assets/Icons_GrayStar.png"></button>
				<button><img src="Assets/Icons_GrayStar.png"></button>
				<button><img src="Assets/Icons_GrayStar.png"></button>
			</div>
			<textarea name="description" rows="10" placeholder="Review description"></textarea>

			<input type="submit" value="Submit">

			<input type="hidden" name="stars" value="1">
			<input type="hidden" name="recipe" value=<?php echo $_GET[ 'id'] ?>>
		</form>
	</main>
	<?php include_once('footer.php'); ?>
</body>
<script src="_js/jquery-3.2.1.min.js"></script>
<script src="_js/universal.js"></script>
<script>
	$( '#review button' ).click( function ( e ) {
		var stars = $( '#review button img' );
		stars.attr( 'src', 'Assets/Icons_GrayStar.png' );
		var index = $( this ).index();
		$( "input[name='stars']" ).val( index + 1 );

		for ( var i = 0; i <= index; i++ ) {
			var a = $( stars.get( i ) ).attr( 'src', 'Assets/Icons_OrangeStar.png' );
		}
		e.preventDefault();
	} )
</script>

</html>