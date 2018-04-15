<!doctype html>
<?php

require_once( 'connstring.php' );
if ( !isset( $_GET[ 'id' ] ) ) {
	header( 'Location: index.php' );
}
if ( isset( $_SESSION[ 'role' ] ) ) {
	$query = "SELECT * FROM FAVOURITE WHERE user = '{$_SESSION['userid']}' AND recipe = '{$_GET['id']}'";
	$result = $conn->query( $query );
	$favourite = $result->num_rows > 0;
}
?>
<html>

<head>
	<meta charset="utf-8">
	<title>View</title>
	<link href="_css/universal.css" rel="stylesheet">
	<link href="_css/view.css" rel="stylesheet">

</head>

<body>
	<?php include_once('nav.php');
	?>
	<main>
		<?php 
		
		$recipe = $_GET['id'];
		$query = "SELECT a.submitter,a.name, a.description, a.preptime, a.servings, a.timeneeded, a.imageext, b.displayName FROM recipe as a JOIN user as b ON a.submitter = b.id WHERE a.id = '$recipe'";
		
		$result = $conn->query($query);
		if($result->num_rows == 0){
			header('Location: index.php');
		}
		
		$row = $result->fetch_assoc();
		
		$submitter = $row['submitter'];
		$name = $row['name'];
		$description = $row['description'];
		$timeneeded = $row['timeneeded'];
		$imageext = $row['imageext'];
		$displayName = $row['displayName'];
		$preptime = $row['preptime'];
		$servings = $row['servings'];
		
		$query = "SELECT avg(stars) as avg FROM review where recipe = '$recipe'";
		$row = $conn->query($query)->fetch_assoc();
		if($row['avg'] == null){
			$stars = 0;
		}
		else {
			$stars = round($row['avg']);
		}
		
		?>

		<header>
			<img src="uploadedPhotos/<?php echo $recipe . '.' . $imageext ?>">
			<section>
				<h2>
					<?php echo $name ?>
				</h2>
				<div id="stars">
					<?php 
					for($i=0; $i<$stars;$i++){ 
						echo '<img class="star" src="Assets/Icons_OrangeStar.png">';
					}
					for($i=0; $i<5 - $stars;$i++){ 
						echo '<img class="star" src="Assets/Icons_GrayStar.png">';
					}
						
					?>
				</div>
				<section>
					<span>
					Recipe by
					<span>
						<?php echo $displayName ?>
					</span>
				

					</span>
					<span id="timeneeded"><img src="Assets/Icons_Clock.png"><span>Prep: <?php echo $preptime ?> minutes,
 				Total: <?php echo $timeneeded . ' minutes' ?></span></span>
				</section>
				<p>
					<?php echo $description ?>
				</p>
				<section id="recipeBtns">
					<?php 
					if(!isset($_SESSION['role']) ){
						echo '<button id="report">Report</button>';
						echo '<button id="favourite">Favourite</button>';
					}
					else if ($_SESSION['role'] == 2){
						echo "<div>";
						echo "<button id='options'>Options<img src='Assets/Icons_WhiteDown Arrow.png'></button>";
						echo "<div id='optionsDropDown'>";
						echo "<a href='editrecipe.php?id=". $recipe . "'>Edit</a>";
						echo "<a href='recipefunctions.php?id=". $recipe . "&operation=delete'>Delete</a>";
						echo "<a href='recipefunctions.php?id=". $recipe . "&operation=censor'>Censor</a>";
						echo "</div>";
						echo "</div>";
					}
					else if ($_SESSION['userid'] == $submitter) {
						echo '<a href="editrecipe?id='. $recipe . '">Edit</a>';
					}
					else if ($_SESSION['role'] == 1) {
						echo '<button id="report">Report</button>';
						echo $favourite ? '<button id="unfavourite">Unfavourite</button>' : '<button id="favourite">Favourite</button>';
					 
					}
					?>
					<br>

				</section>
				<p id="servings">Servings:
					<?php echo $servings ?>
				</p>

			</section>
		</header>

		<section id="ingredients">
			<h3>Ingredients</h3>
			<section>
				<?php 
				$query2 = "SELECT * FROM ingredient WHERE recipe = '$recipe'";
				$result2 = $conn->query($query2);
				while($row2 = $result2->fetch_assoc()){
					echo "<div>{$row2['ingredient']}</div>";
				}
				?>
			</section>
		</section>
		<section id="steps">
			<h3>Steps</h3>
			<section>
				<?php 
				$query2 = "SELECT * FROM steps WHERE recipeId = '$recipe' ORDER BY position";
				$counter = 0;
				$result2 = $conn->query($query2);
				while($row2 = $result2->fetch_assoc()){
					$counter ++;
					echo "<div>$counter. {$row2['description']}</div>";
				}
				?>
			</section>
		</section>
		<section id="Reviews">
			<h3>Reviews</h3>
			<section>
				<?php 
				$query2 = "SELECT a.id,a.submitter, a.stars,a.review,b.displayName FROM review as a JOIN user as b ON a.submitter = b.id WHERE recipe = '$recipe'  ORDER BY date";
				$result2 = $conn->query($query2);
				while($row2 = $result2->fetch_assoc()){
					echo "<div class='review' id='{$row2['id']}'>";
					echo "<span class='name'>{$row2['displayName']}</span>";
					echo "<span class='reviewstars'>";
					for($i = 0; $i<$stars; $i++){
						echo "<img class='reviewstar' src='Assets/Icons_OrangeStar.png'>";
					}
					for($i = 0; $i<5-$stars; $i++){
						echo "<img class = 'reviewstar' src='Assets/Icons_GrayStar.png'>";
					}
					echo "</span>";
					echo "<p class='reviewdesc'>" . $row2['review'] . "</p>";
					if($row2['submitter'] == $_SESSION['userid'] or $_SESSION['role'] > 1) {
						echo "<button class='delete'>Delete</button>";
					}
					echo "</div>";
				}
				?>
			</section>
			<?php 
			echo "<a href='writeReview.php?id=" . $recipe . "'>Write Review</a>";
			?>
		</section>
	</main>
	<?php include_once('footer.php'); ?>
</body>
<script src="_js/jquery-3.2.1.min.js"></script>
<script src="_js/universal.js"></script>
<script>
	if ( $( '#options' ) != undefined ) {
		$( '#options' ).click( function () {
			$( '#optionsDropDown' ).slideToggle( 200 );
		} )
	}

	$( ".delete" ).click( function ( e ) {
		var id = $( e.target ).parent().attr( 'id' );

		$.ajax( {
			url: 'recipefunctions.php',
			data: {
				id: id,
				operation: "deletereview"
			}}).done( function (result) {
				$(e.target).parent().remove();
			} )
		} )


	$( "#recipeBtns button" ).click( function ( e ) {
		if ( e.target.id == "report" ) {
			$.ajax( {
				url: 'recipefunctions.php',
				data: {
					id: '<?php echo $recipe ?>',
					operation: 'report'
				}
			} ).done( function () {
				e.target.parentElement.removeChild( e.target );
			} );
		}
		if ( e.target.id == "favourite" ) {
			$.ajax( {
				url: 'recipefunctions.php',
				data: {
					id: '<?php echo $recipe ?>',
					operation: 'favourite'
				}
			} ).done( function () {
				e.target.textContent = 'Unfavourite';
				e.target.id = 'unfavourite'
			} );
		};
		if ( e.target.id == "unfavourite" ) {
			$.ajax( {
				url: 'recipefunctions.php',
				data: {
					id: '<?php echo $recipe ?>',
					operation: 'unfavourite'
				}
			} ).done( function () {
				e.target.textContent = 'Favourite';
				e.target.id = 'favourite'
			} );
		};
	} )
</script>

</html>